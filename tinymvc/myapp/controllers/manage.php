<?php
require_once('/configs/conf.php');
require_once('tinymvc/myapp/models/access_model.php');
require_once('tinymvc/myapp/models/manage_model.php');

class Manage_controller extends TinyMVC_Controller
{
    function index(){
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $title = '';
        $titleModule = 'Администрирование';
        $navigationLinks = 'manage';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);
        //print_r($menu);
        //print_r($_SESSION);

        $this->smarty->assign('content', '/tpl/manage/modules.tpl.html');
        $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
        $this->smarty->assign('menu', $menu);
        $this->smarty->assign('title', $title);
        $this->smarty->assign('titleModule', $titleModule);
        $this->smarty->assign('navigationLinks', $navigationLinks);
        $this->smarty->display('layout.html');
    }

    //information
    function info(){
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $title = '';
        $titleModule = 'Информация';
        $navigationLinks = 'info';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);
        //print_r($menu);
        //print_r($_SESSION);

        $this->smarty->assign('content', '/tpl/manage/modules.tpl.html');
        $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
        $this->smarty->assign('head', $navigationLinks);
        $this->smarty->assign('menu', $menu);
        $this->smarty->assign('title', $title);
        $this->smarty->assign('titleModule', $titleModule);
        $this->smarty->assign('navigationLinks', $navigationLinks);
        $this->smarty->display('layout.html');
    }

    function info_history(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();


        $title = '';
        $titleModule = 'Информация';
        $navigationLinks = 'info_history';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);

        $dateFrom = isset($_GET['dateFrom']) && !empty($_GET['dateFrom']) ? trim($_GET['dateFrom']) : date('01.m.20y', strtotime(date("m.d.y"))); //start month
        $dateTo = isset($_GET['dateTo']) && !empty($_GET['dateTo']) ? trim($_GET['dateTo']) : date('t.m.20y', strtotime(date("m.d.y"))); //end month
        $platform = isset($_GET['platform']) && !empty($_GET['platform']) ? $_GET['platform'] : '';

        $filters = [
            'date_to' => $dateFrom,
            'date_do' => $dateTo,
            'platform' => $platform
        ];

        $data = $this->manage->getInfoHistory($filters);
        //print_r($data);
        $this->smarty->assign('data', $data);

        $this->smarty->assign('content', '/tpl/manage/info_history.tpl.html');
        $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
        $this->smarty->assign('head', $navigationLinks);
        $this->smarty->assign('menu', $menu);
        $this->smarty->assign('title', $title);
        $this->smarty->assign('titleModule', $titleModule);
        $this->smarty->assign('navigationLinks', $navigationLinks);
        $this->smarty->display('layout.html');
    }

    function info_manual_priority(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();


        $title = '';
        $titleModule = 'Сортировка';
        $navigationLinks = 'info_manuals';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);

        $pid = isset($_GET['pid']) && !empty($_GET['pid'] && $_GET['pid'] != 'undefined') ? $_GET['pid'] : '';
        //print_r($pid.'fdsfs');
        $filters = [
            'pid' => $pid
        ];

        $data = $this->manage->info_manual_priority($filters);
        //print_r($data);
        $this->smarty->assign('data', $data);

        $this->smarty->assign('content', '/tpl/manage/info_manual_priority.tpl.html');
        //$this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
        $this->smarty->assign('head', $navigationLinks);
        $this->smarty->assign('menu', $menu);
        $this->smarty->assign('title', $title);
        $this->smarty->assign('standalone', '1');
        $this->smarty->assign('titleModule', $titleModule);
        $this->smarty->assign('navigationLinks', $navigationLinks);
        $this->smarty->display('layout.html');
    }

    function info_manuals_set_sort(){
        $this->access = new Access_Model();
        $this->db_ora = new Db_ora_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();
        if ($_POST) {
            $id = (isset($_POST['id'])) ? $_POST['id'] : die("not need vars [badrow]");
            $sort = (isset($_POST['sort'])) ? $_POST['sort'] : die("not need vars [badval]");
            $par = array("id" => $id, "sort" => $sort);
            $sql = <<<SQL
            UPDATE stat.WEB_HELP_TUTORIAL_TREE SET priority = :sort
            where 1=1
            and id = :id
SQL;
            if (!$res = $this->db_ora->go_query2($sql, $par)) {
                echo($this->db_ora->error);
                die("up ok");
            }
        }
    }

    function formManual(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();
        $mode = isset($_GET['mode']) && !empty($_GET['mode']) ? $_GET['mode'] : user_error('[mode] is empty');
        //print_r($_POST['vis']);die();
        if ($mode == 'create' || $mode == 'update') {
            $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : user_error('[name] is empty');
            $vis = ($_POST['vis'] == 'true') ? "1" : "0";;
            $body = isset($_POST['body']) && !empty($_POST['body']) ? $_POST['body'] : '';
            $pid = isset($_POST['pid']) && !empty($_POST['pid']) ? $_POST['pid'] : '';
            $act = isset($_POST['act']) && !empty($_POST['act']) ? $_POST['act'] : '';
        }
        //print_r($vis);
        $form = [
            'id' => $id,
            'name' => $name,
            'pid' => $pid,
            'vis' => $vis,
            'body' => $body,
        ];

        if ($act == 'new') {
            $this->manage->createManual($form);
        } else{
            $form['id'] = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            //print_r($form);
            $this->manage->updateManual($form);
        }
    }

    function formRefWorktypes(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();
        $mode = isset($_GET['mode']) && !empty($_GET['mode']) ? $_GET['mode'] : user_error('[mode] is empty');
        //print_r($_POST['vis']);die();
        if ($mode == 'create' || $mode == 'update') {
            $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : user_error('[name] is empty');
            $types = isset($_POST['types']) && !empty($_POST['types']) ? $_POST['types'] : '';
            $jobcategory = isset($_POST['jobcategory']) && !empty($_POST['jobcategory']) ? $_POST['jobcategory'] : '';
            $safetymeasures = isset($_POST['safetymeasures']) && !empty($_POST['safetymeasures']) ? $_POST['safetymeasures'] : '';
            $pid = isset($_POST['pid']) && !empty($_POST['pid']) ? $_POST['pid'] : '';
            $act = isset($_POST['act']) && !empty($_POST['act']) ? $_POST['act'] : '';

        }
        $form = [
            'id' => $id,
            'pid' => $pid,
            'name' => $name,
            'types' => $types,
            'jobcategory' => $jobcategory,
            'safetymeasures' => $safetymeasures
        ];

        if ($act == 'new') {
            $this->manage->createRefWorktypes($form);
        } else{
            $form['id'] = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            //print_r($form);
            $this->manage->updateRefWorktypes($form);
        }
    }

    function formRefAnySafety(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();
        $mode = isset($_GET['mode']) && !empty($_GET['mode']) ? $_GET['mode'] : user_error('[mode] is empty');
        //print_r($_POST['vis']);die();
        if ($mode == 'create' || $mode == 'update') {
            $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            //
            $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : user_error('[name] is empty');
            $divid = isset($_POST['divid']) && !empty($_POST['divid']) ? $_POST['divid'] : '';
            //
            $pid = isset($_POST['pid']) && !empty($_POST['pid']) ? $_POST['pid'] : '';
            $act = isset($_POST['act']) && !empty($_POST['act']) ? $_POST['act'] : '';

        }
        $form = [
            'id' => $id,
            //'pid' => $pid,
            'name' => $name,
            'divid' => $divid
        ];

        if ($act == 'new') {
            $this->manage->createRefAnySafety($form);
        } else{
            $form['id'] = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            //print_r($form);
            $this->manage->updateRefAnySafety($form);
        }
    }


    function formRefTaskSafety(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();
        $mode = isset($_GET['mode']) && !empty($_GET['mode']) ? $_GET['mode'] : user_error('[mode] is empty');
        //print_r($_POST['vis']);die();
        if ($mode == 'create' || $mode == 'update') {
            $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            //
            $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : user_error('[name] is empty');
            $divid = isset($_POST['divid']) && !empty($_POST['divid']) ? $_POST['divid'] : '';
            //
            $pid = isset($_POST['pid']) && !empty($_POST['pid']) ? $_POST['pid'] : '';
            $act = isset($_POST['act']) && !empty($_POST['act']) ? $_POST['act'] : '';

        }
        $form = [
            'id' => $id,
            //'pid' => $pid,
            'name' => $name,
           // 'divid' => $divid
        ];

        if ($act == 'new') {
            $this->manage->createRefTaskSafety($form);
        } else{
            $form['id'] = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            //print_r($form);
            $this->manage->updateRefTaskSafety($form);
        }
    }

    function formRefEmployees(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();
        $mode = isset($_GET['mode']) && !empty($_GET['mode']) ? $_GET['mode'] : user_error('[mode] is empty');
        //print_r($_POST['vis']);die();
        if ($mode == 'create' || $mode == 'update') {
            $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            $fam = isset($_POST['fam']) && !empty($_POST['fam']) ? $_POST['fam'] : user_error('[fam] is empty');
            $im = isset($_POST['im']) && !empty($_POST['im']) ? $_POST['im'] : user_error('[im] is empty');
            $otch = isset($_POST['otch']) && !empty($_POST['otch']) ? $_POST['otch'] : '';
            $enterpiseId = isset($_POST['enterpiseId']) && !empty($_POST['enterpiseId']) ? $_POST['enterpiseId'] : user_error('[enterpiseId] is empty');
            $divisionId = isset($_POST['divisionId']) && !empty($_POST['divisionId']) ? $_POST['divisionId'] : user_error('[divisionId] is empty');
            $postId = isset($_POST['postId']) && !empty($_POST['postId']) ? $_POST['postId'] : user_error('[postId] is empty');
            $rclass = isset($_POST['rclass']) && !empty($_POST['rclass']) ? $_POST['rclass'] : '';
            $tabnum = isset($_POST['tabnum']) && !empty($_POST['tabnum']) ? $_POST['tabnum'] : '';
            $categoryId = isset($_POST['categoryId']) && !empty($_POST['categoryId']) ? $_POST['categoryId'] : '';
            $phone = isset($_POST['phone']) && !empty($_POST['phone']) ? $_POST['phone'] : '';
            $mail = isset($_POST['mail']) && !empty($_POST['mail']) ? $_POST['mail'] : '';
            $birthdate = isset($_POST['birthdate']) && !empty($_POST['birthdate']) ? $_POST['birthdate'] : '';
            $hireddate = isset($_POST['hireddate']) && !empty($_POST['hireddate']) ? $_POST['hireddate'] : '';
            $sexId = isset($_POST['sexId']) && !empty($_POST['sexId']) ? $_POST['sexId'] : '';
            $pid = isset($_POST['pid']) && !empty($_POST['pid']) ? $_POST['pid'] : '';
            $act = isset($_POST['act']) && !empty($_POST['act']) ? $_POST['act'] : '';

        }
        //print_r($vis);
        $form = [
            'id' => $id,
            'fam' => $fam,
            'im' => $im,
            'otch' => $otch,
            'enterpiseId' => $enterpiseId,
            'divisionId' => $divisionId,
            'postId' => $postId,
            'rclass' => $rclass,
            'tabnum' => $tabnum,
            'categoryId' => $categoryId,
            'phone' => $phone,
            'mail' => $mail,
            'birthdate' => $birthdate,
            'hireddate' => $hireddate,
            'sexId' => $sexId
        ];

        if ($act == 'new') {
            $this->manage->createRefEmployee($form);
        } else{
            $form['id'] = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
            //print_r($form);
            $this->manage->updateRefEmployee($form);
        }
    }

    function info_manuals(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->db_ora = new DB_ora_Model();
        $this->access->checkAccess();

        $title = '';
        $titleModule = 'Информация';
        $navigationLinks = 'info_manuals';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);

        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';
        switch ($action) {
            case 'getList':
                break;
            case 'delData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $this->manage->info_manuals_delManual($id);
                break;
            case 'getData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $dir = "./media/manuals/";
                $file = $dir.$id.".html";
                if (file_exists($file)) {
                    $fp = file_get_contents($file);
                    $sql = <<<SQL
                    SELECT 
                        T.ID,
                        T.PID,
                        T.NAME,
                        T.DEL,
                        T.PRIORITY,
                        case when T.VIS = '1' then 'checked' 
                        else '' end vis
                    FROM STAT.WEB_HELP_TUTORIAL_TREE T
                    WHERE ID = $id
SQL;
                    if (!$data = $this->db_ora->go_result_once($sql)) echo($this->db_ora->error);
                    $data["TUTORIAL"] = $fp;
                }else {
                    $data = $this->manage->info_manuals_getManual($id);
                    if (!empty($data['TUTORIAL']))
                        $data['TUTORIAL'] = $data['TUTORIAL']->load();
                }
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'newData':
                $pid = isset($_GET['pid']) && !empty($_GET['pid']) ? $_GET['pid'] : 'null';
                $data = $this->manage->info_manuals_new($pid);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            default :
                $data = array();
                $this->smarty->assign('content', '/tpl/manage/info_manuals.tpl.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
                $this->smarty->assign('head', $navigationLinks);
                $this->smarty->assign('data', $data);
                $this->smarty->assign('menu', $menu);
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    //references

    function ref_task_safety(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();

        $title = '';
        $titleModule = 'Меры безопасности';
        $navigationLinks = 'references';
        $menu = $this->access->get_module_menu('dsf', $navigationLinks);
        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';
        switch ($action) {
            case 'getList':
                break;
            case 'delData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $this->manage->ref_TaskSafety_del($id);
                break;
            case 'getData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $data = $this->manage->ref_TaskSafety_get($id);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'newData':
                $pid = isset($_GET['pid']) && !empty($_GET['pid']) ? $_GET['pid'] : 'null';
                $data = $this->manage->ref_TaskSafety_new($pid);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            default :
                $data = array();
                $this->smarty->assign('content', '/tpl/manage/ref_task_safety.tpl.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
                $this->smarty->assign('head', $navigationLinks);
                $this->smarty->assign('divisions', $this->manage->get_divisions());
                $this->smarty->assign('data', $data);
                $this->smarty->assign('menu', $menu);
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function ref_anysafety(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();

        $title = '';
        $titleModule = 'Дополнительные меры безопасности';
        $navigationLinks = 'references';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);

        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';
        switch ($action) {
            case 'getList':
                break;
            case 'delData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $this->manage->ref_anySafety_del($id);
                break;
            case 'getData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $data = $this->manage->ref_anySafety_get($id);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'newData':
                $pid = isset($_GET['pid']) && !empty($_GET['pid']) ? $_GET['pid'] : 'null';
                $data = $this->manage->ref_anySafety_new($pid);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            default :
                $data = array();
                $this->smarty->assign('content', '/tpl/manage/ref_anysafety.tpl.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
                $this->smarty->assign('head', $navigationLinks);
                $this->smarty->assign('divisions', $this->manage->get_divisions());
                $this->smarty->assign('data', $data);
                $this->smarty->assign('menu', $menu);
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function ref_worktypes(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();

        $title = '';
        $titleModule = 'Виды работ';
        $navigationLinks = 'references';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);

        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';
        switch ($action) {
            case 'getList':
                break;
            case 'delData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $this->manage->ref_worktypes_del($id);
                break;
            case 'getData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $data = $this->manage->ref_worktypes_get($id);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'newData':
                $pid = isset($_GET['pid']) && !empty($_GET['pid']) ? $_GET['pid'] : 'null';
                $data = $this->manage->ref_worktypes_new($pid);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            default :
                $data = array();
                $this->smarty->assign('content', '/tpl/manage/ref_worktypes.tpl.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
                $this->smarty->assign('head', $navigationLinks);
                $this->smarty->assign('data', $data);
                $this->smarty->assign('menu', $menu);
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function ref_employees(){
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->access->checkAccess();

        $title = '';
        $titleModule = 'Сотрудники';
        $navigationLinks = 'references';

        $menu = $this->access->get_module_menu('dsf', $navigationLinks);

        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';
        switch ($action) {
            case 'getList':
                break;
            case 'delData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $this->manage->ref_employee_del($id);
                break;
            case 'getData':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $data = $this->manage->ref_employee_get($id);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'newData':
                $pid = isset($_GET['pid']) && !empty($_GET['pid']) ? $_GET['pid'] : 'null';
                $data = $this->manage->ref_employee_new($pid);
                print_r(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            default :
                $data = array();
                $this->smarty->assign('content', '/tpl/manage/ref_employees.tpl.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.main.html');
                $this->smarty->assign('head', $navigationLinks);
                $this->smarty->assign('data', $data);
                $this->smarty->assign('menu', $menu);
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function ref_posts(){
        $this->manage = new Manage_Model();
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getList':
                $list = $this->manage->getPosts();
                print_r(json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'updateVariables' || 'insertVariables':
                $id = $action == 'updateVariables' ? isset($_POST['KOD']) && !empty($_POST['KOD']) ? $_POST['KOD'] : user_error('[KOD] in empty') : '';
                $text = isset($_POST['TEXT']) ? $_POST['TEXT'] : '';
                $itr = isset($_POST['ITR']) ? filter_var($_POST['ITR'], FILTER_VALIDATE_BOOLEAN) : '';
                $level = isset($_POST['LEVEL_PK']) ? $_POST['LEVEL_PK'] : '';
                $val1 = isset($_POST['VAL1']) ? filter_var($_POST['VAL1'], FILTER_VALIDATE_BOOLEAN) : '';
                $val2 = isset($_POST['VAL2']) ? filter_var($_POST['VAL2'], FILTER_VALIDATE_BOOLEAN) : '';
                $val3 = isset($_POST['VAL3']) ? filter_var($_POST['VAL3'], FILTER_VALIDATE_BOOLEAN) : '';
                $val4 = isset($_POST['VAL4']) ? filter_var($_POST['VAL4'], FILTER_VALIDATE_BOOLEAN) : '';
                $val5 = isset($_POST['VAL5']) ? filter_var($_POST['VAL5'], FILTER_VALIDATE_BOOLEAN) : '';
                $val6 = isset($_POST['VAL6']) ? filter_var($_POST['VAL6'], FILTER_VALIDATE_BOOLEAN) : '';
                $val7 = isset($_POST['VAL7']) ? filter_var($_POST['VAL7'], FILTER_VALIDATE_BOOLEAN) : '';

                $form = [
                    'id' => $id,
                    'text' => $text,
                    'itr' => $itr,
                    'level' => $level,
                    'val1' => $val1,
                    'val2' => $val2,
                    'val3' => $val3,
                    'val4' => $val4,
                    'val5' => $val5,
                    'val6' => $val6,
                    'val7' => $val7,
                ];

                if($action == 'updateVariables'){
                    $updated = $this->manage->updatePost($form);
                    print_r(json_encode($updated, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }else if($action == 'insertVariables'){
                    $inserted = $this->manage->insertPost($form);
                    print_r(json_encode($inserted, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }
                break;
            case 'remove':
                $id = isset($_POST['KOD']) && !empty($_POST['KOD']) ? $_POST['KOD'] : user_error('[KOD] in empty');
                $this->manage->removePost($id);
                break;
            default :
                $title = 'Администрирование';
                $titleModule = 'Администрирование';
                $navigationLinks = 'references';
                $this->smarty->assign('content', '/tpl/manage/ref.posts.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.ref.html');
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function documents(){
        $this->manage = new Manage_Model();
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getList':
                break;
            default :
                $title = 'Администрирование';
                $titleModule = 'Администрирование';
                $navigationLinks = 'references';
                $this->smarty->assign('content', '/tpl/manage/ref.documents.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.ref.html');
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function violationCategory(){
        $this->manage = new Manage_Model();
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getList':
                break;
            case 'getCategory':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : user_error('[id] is empty');
                $category = $this->manage->getViolationCategory($id);
                print_r(json_encode($category, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            default :
                $title = 'Администрирование';
                $titleModule = 'Администрирование';
                $navigationLinks = 'references';
                $this->smarty->assign('content', '/tpl/manage/ref.violationCategory.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.ref.html');
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function references(){
        $this->manage = new Manage_Model();
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $this->ref_employees();
    }

    function techcards(){
        $this->task = new Task_Model();
        $this->manage = new Manage_Model();
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getList':
                $type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : false;
                $impactCode = isset($_GET['impact']) && !empty($_GET['impact']) ? $_GET['impact'] : false;

                $filters = [
                    'type' => $type,
                    'impact' => $impactCode
                ];

                $list = $this->manage->getGettechcards($filters);
                print_r(json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'updateVariables' || 'insertVariables':

            default :
                $filterType = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : '';
                $filterImpact = isset($_GET['impact']) && !empty($_GET['impact']) ? $_GET['impact'] : '';

                $types = $this->task->getTechCardTypes();
                $impacts = $this->task->getImpacts();

                $title = 'Администрирование';
                $titleModule = 'Администрирование';
                $navigationLinks = 'references';
                $this->smarty->assign('content', '/tpl/manage/ref.techcards.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.techcards.html');
                $this->smarty->assign('filterType', $filterType);
                $this->smarty->assign('filterImpact', $filterImpact);
                $this->smarty->assign('types', $types);
                $this->smarty->assign('impacts', $impacts);
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    function materials(){
        $this->task = new Task_Model();
        $this->manage = new Manage_Model();
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getList':
                $list = $this->manage->getMaterials();
                print_r(json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;
            case 'updateVariables' || 'insertVariables':

            default :
                $filterType = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : '';
                $filterImpact = isset($_GET['impact']) && !empty($_GET['impact']) ? $_GET['impact'] : '';

                $types = $this->task->getTechCardTypes();
                $impacts = $this->task->getImpacts();

                $title = 'Администрирование';
                $titleModule = 'Администрирование';
                $navigationLinks = 'references';
                $this->smarty->assign('content', '/tpl/manage/ref.materials.html');
                $this->smarty->assign('navigation', '/tpl/manage/navigation.materials.html');
                $this->smarty->assign('filterType', $filterType);
                $this->smarty->assign('filterImpact', $filterImpact);
                $this->smarty->assign('types', $types);
                $this->smarty->assign('impacts', $impacts);
                $this->smarty->assign('title', $title);
                $this->smarty->assign('titleModule', $titleModule);
                $this->smarty->assign('navigationLinks', $navigationLinks);
                $this->smarty->display('layout.html');
        }
    }

    //references
/*
    function ref_employees_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = "where UPPER(t.tabel_kadr) like UPPER('%$find%') or UPPER(t.NAME) like UPPER('%$find%')  or UPPER(t.POST_NAME) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select * from (select null priority, CONCAT('E', PREDPR_K) id, null pid, PREDPR_NAIM name, 'E' identifier , null post_name, null tabel_kadr
                from PREDPR
                where del is null
                union all
                select null priority, CONCAT('D', UCHAST_K) id, CONCAT('E', PREDPR_K) pid, UCHAST_NAIM name, 'D' identifier, null post_name, null tabel_kadr
                from UCHAST u
                where u.del is null
                union all
                --select null priority, CONCAT('S', SOTRUD_K) id, CONCAT('D', UCHAST_K) pid, stat.pack.sotrud_fio_poln(sotrud_k) name, 'S' identifier, stat.pack.dolj_naim(dolj_k) post_name, b.tabel_kadr
                select null priority, CONCAT('', SOTRUD_K) id, CONCAT('D', UCHAST_K) pid, stat.pack.sotrud_fio_poln(sotrud_k) name, 'S' identifier, stat.pack.dolj_naim(dolj_k) post_name, b.tabel_kadr
                from SOTRUD b
                where b.del is null
                order by priority, name) t
                $findsql
SQL;
            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cat["IDENT"] = $res[$i]["IDENTIFIER"];
                    $cat["POST"] = $res[$i]["POST_NAME"];
                    $cat["TABNUM"] = $res[$i]["TABEL_KADR"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            //print_r($cats);die();
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->ref_employees_build_tree($cats, 0));
                    //$ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                    $ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->ref_employees_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }*/

    function ref_employees4division_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = "where UPPER(t.tabel_kadr) like UPPER('%$find%') or UPPER(t.NAME) like UPPER('%$find%')  or UPPER(t.POST_NAME) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select * from (
                select null priority, CONCAT('P', KOD) id, null pid, TEXT name, 'P' identifier, null post_name, null tabel_kadr
                from DOLJNOST u
                where u.del is null
                union all
                select null priority, CONCAT('', SOTRUD_K) id, CONCAT('P', DOLJ_K) pid, stat.pack.sotrud_fio_poln(sotrud_k) name, 'S' identifier, stat.pack.dolj_naim(dolj_k) post_name, b.tabel_kadr
                from SOTRUD b
                where b.del is null
                order by priority, name) t
                $findsql
SQL;

            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cat["IDENT"] = $res[$i]["IDENTIFIER"];
                    $cat["POST"] = $res[$i]["POST_NAME"];
                    $cat["TABNUM"] = $res[$i]["TABEL_KADR"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            //print_r($cats);die();
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->ref_employees4division_build_tree($cats, 0));
                    //$ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                    $ret .= ($this->manage->ref_employees4division_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->ref_employees4division_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }

    function ref_employees4steps_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = "where UPPER(t.tabel_kadr) like UPPER('%$find%') or UPPER(t.NAME) like UPPER('%$find%')  or UPPER(t.POST_NAME) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select * from (select CONCAT('G', id) id, null pid, name, 'G' identifier, null post_name, null tabel_kadr
                from EMPLOYEE_GROUP u
                where u.del is null
                union all
                select CONCAT('', EMPLOYEE_ID) id, CONCAT('G', EMPLOYEE_GROUP_ID) pid, 
                       stat.pack.sotrud_fio_poln(EMPLOYEE_ID) name, 
                'S' identifier, 
                       stat.pack.uchast_naim(uchast_K) post_name, b1.tabel_kadr
                from EMPLOYEE_CONN_GROUP b
                left join SOTRUD b1 on b.employee_id = b1.sotrud_k
                order by name
                ) t
                $findsql
SQL;

            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cat["IDENT"] = $res[$i]["IDENTIFIER"];
                    $cat["POST"] = $res[$i]["POST_NAME"];
                    $cat["TABNUM"] = $res[$i]["TABEL_KADR"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            //print_r($cats);die();
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->ref_employees4steps_build_tree($cats, 0));
                    //$ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                    $ret .= ($this->manage->ref_employees4steps_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->ref_employees4steps_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }

    //references

    function ref_anysafety_get_table(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        //$this->access->checkAccess();

        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";
            $divid_sql = (isset($_GET['divid2']) && !empty($_GET['divid2']) && $_GET['divid2']!='undefined') ? " and division_id = ".$_GET['divid2']."" : "";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = " and UPPER(t.TEXT) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select t.id,
                t.text name,
                stat.pack.uchast_naim(t.division_id) division 
                from ORDERS_ARRANGEMENT t
                where del is null                
                $findsql $divid_sql
                order by stat.pack.uchast_naim(t.division_id), name
SQL;
            //die($_REQUEST);
            if (!$res = $this->db_ora->go_result($sql)) {
                $ret = "<ul style='padding-left: 22px;'><li>" . order_ref_no_find_data.  "</li></ul>";
            } else {
                $ret = '
                <table border="0" class="table table-stripe1d  tac" id="table">
                <!--<tr class="info head_rep">
                    <th colspan="3" style="text-align: left;">Дополнительные меры безопасности</th>
                </tr>-->
                <tr class="info head_rep">
                    <th>ID</th>
                    <th>Подразделение</th>
                    <th>Наименование</th>
                </tr>
                ';
                $i=0;
                while ($i < count($res)) {
                    $ret .= '<tr class="itemtab" onclick="openRequest('. $res[$i]["ID"].')"><td>'. $res[$i]["ID"].'</td><td style="text-align: left;">'. $res[$i]["DIVISION"].'</td><td style="text-align: left;">'. $res[$i]["NAME"].'</td><tr>';

                    $i++;
                }
                $ret .= '</table>';
            }

            die($ret);
        } else {
            die("Error!");
        }
    }

    function ref_task_safety_get_table(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        //$this->access->checkAccess();

        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";
            $divid_sql = (isset($_GET['divid2']) && !empty($_GET['divid2']) && $_GET['divid2']!='undefined') ? " and division_id = ".$_GET['divid2']."" : "";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = " and UPPER(t.TEXT) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select t.id,
                t.text name
                from MECHANIC_FILEDS_ARRANGEMENTS t
                where del is null                
                $findsql $divid_sql
                order by name
SQL;
            //die($_REQUEST);
            if (!$res = $this->db_ora->go_result($sql)) {
                $ret = "<ul style='padding-left: 22px;'><li>" . order_ref_no_find_data.  "</li></ul>";
            } else {
                $ret = '
                <table border="0" class="table table-stripe1d  tac" id="table">
                <tr class="info head_rep">
                    <th>ID</th>
                    <th>Наименование</th>
                </tr>
                ';
                $i=0;
                while ($i < count($res)) {
                    $ret .= '<tr class="itemtab" onclick="openRequest('. $res[$i]["ID"].')"><td>'. $res[$i]["ID"].'</td><td style="text-align: left;">'. $res[$i]["NAME"].'</td><tr>';

                    $i++;
                }
                $ret .= '</table>';
            }

            die($ret);
        } else {
            die("Error!");
        }
    }

    function ref_worktypes_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = "where UPPER(t.NAME) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select * from (
                    select
                    zamech_k id,
                    pref_k pid,
                    zamech_naim name
                    from ZAMECH t
                    where predpis_tip_k = 3 and del is null-- and uchast_tip_k = 1
                ) t
                $findsql
SQL;
            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            //print_r($cats);die();
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->ref_worktypes_build_tree($cats, 0));
                    //$ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                    $ret .= ($this->manage->ref_worktypes_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->ref_worktypes_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }

    function ref_employees_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = "where UPPER(t.tabel_kadr) like UPPER('%$find%') or UPPER(t.NAME) like UPPER('%$find%')  or UPPER(t.POST_NAME) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select * from (select null priority, CONCAT('E', PREDPR_K) id, null pid, PREDPR_NAIM name, 'E' identifier , null post_name, null tabel_kadr
                from PREDPR
                where del is null
                union all
                select null priority, CONCAT('D', UCHAST_K) id, CONCAT('E', PREDPR_K) pid, UCHAST_NAIM name, 'D' identifier, null post_name, null tabel_kadr
                from UCHAST u
                where u.del is null
                union all
                --select null priority, CONCAT('S', SOTRUD_K) id, CONCAT('D', UCHAST_K) pid, stat.pack.sotrud_fio_poln(sotrud_k) name, 'S' identifier, stat.pack.dolj_naim(dolj_k) post_name, b.tabel_kadr
                select null priority, CONCAT('', SOTRUD_K) id, CONCAT('D', UCHAST_K) pid, stat.pack.sotrud_fio_poln(sotrud_k) name, 'S' identifier, stat.pack.dolj_naim(dolj_k) post_name, b.tabel_kadr
                from SOTRUD b
                where b.del is null
                order by priority, name) t
                $findsql
SQL;
            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cat["IDENT"] = $res[$i]["IDENTIFIER"];
                    $cat["POST"] = $res[$i]["POST_NAME"];
                    $cat["TABNUM"] = $res[$i]["TABEL_KADR"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            //print_r($cats);die();
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->ref_employees_build_tree($cats, 0));
                    //$ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                    $ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->ref_employees_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }
/*
    function ref_employees4division_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = "where UPPER(t.tabel_kadr) like UPPER('%$find%') or UPPER(t.NAME) like UPPER('%$find%')  or UPPER(t.POST_NAME) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select * from (
                select null priority, CONCAT('P', KOD) id, null pid, TEXT name, 'P' identifier, null post_name, null tabel_kadr
                from DOLJNOST u
                where u.del is null
                union all
                select null priority, CONCAT('', SOTRUD_K) id, CONCAT('P', DOLJ_K) pid, stat.pack.sotrud_fio_poln(sotrud_k) name, 'S' identifier, stat.pack.dolj_naim(dolj_k) post_name, b.tabel_kadr
                from SOTRUD b
                where b.del is null
                order by priority, name) t
                $findsql
SQL;

            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cat["IDENT"] = $res[$i]["IDENTIFIER"];
                    $cat["POST"] = $res[$i]["POST_NAME"];
                    $cat["TABNUM"] = $res[$i]["TABEL_KADR"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            //print_r($cats);die();
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->ref_employees4division_build_tree($cats, 0));
                    //$ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                    $ret .= ($this->manage->ref_employees4division_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->ref_employees4division_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }*/
/*
    function ref_employees4steps_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = "where UPPER(t.tabel_kadr) like UPPER('%$find%') or UPPER(t.NAME) like UPPER('%$find%')  or UPPER(t.POST_NAME) like UPPER('%$find%') ";
            }
            $sql = <<<SQL
                select * from (select CONCAT('G', id) id, null pid, name, 'G' identifier, null post_name, null tabel_kadr
                from EMPLOYEE_GROUP u
                where u.del is null
                union all
                select CONCAT('', EMPLOYEE_ID) id, CONCAT('G', EMPLOYEE_GROUP_ID) pid, 
                       stat.pack.sotrud_fio_poln(EMPLOYEE_ID) name, 
                'S' identifier, 
                       stat.pack.uchast_naim(uchast_K) post_name, b1.tabel_kadr
                from EMPLOYEE_CONN_GROUP b
                left join SOTRUD b1 on b.employee_id = b1.sotrud_k
                order by name
                ) t
                $findsql
SQL;

            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cat["IDENT"] = $res[$i]["IDENTIFIER"];
                    $cat["POST"] = $res[$i]["POST_NAME"];
                    $cat["TABNUM"] = $res[$i]["TABEL_KADR"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            //print_r($cats);die();
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->ref_employees4steps_build_tree($cats, 0));
                    //$ret .= ($this->manage->ref_employees_build_tree($cats, ''));
                    $ret .= ($this->manage->ref_employees4steps_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->ref_employees4steps_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }*/

    //infoooo

    function info_manuals_get_tree(){
        $this->load->model('Db_ora_Model', 'db_ora');
        $this->load->model('Manage_Model', 'manage');
        //valid auth or not?
        $this->load->model('Access_Model','access');
        $this->access->checkAccess();
        //if ($_POST){
        if ($_POST) {
            $find = (isset($_POST['find']) && !empty($_POST['find'])) ? $_POST['find'] : "empty";

            $sql = <<<SQL
			select uchast_tip_k
			from stat.uchast where uchast_k = :division 
SQL;
            $find = $find;
            if ($find == 'empty') {
                $findsql = " ";
            } else {
                $findsql = " and (UPPER(NAME) LIKE UPPER('%$find%'))";
            }
            $sql = <<<SQL
                SELECT 
                    ID, 
                    PID, 
                    NAME,
                    URL,
                    VIS
                FROM STAT.WEB_HELP_TUTORIAL_TREE
                WHERE DEL is null
                --AND VIS = 1
                 $findsql
                ORDER BY PRIORITY, NAME
SQL;

            if (!$res = $this->db_ora->go_result($sql)) {
                echo($this->db_ora->error);
            } else {
                $cats = Array();
                $cat = Array();
                $prefs = Array();
                $i = 0;
                while ($i < count($res)) {
                    $cat["ID"] = $res[$i]["ID"];
                    $cat["PID"] = $res[$i]["PID"];
                    $prefs[] = $cat["PID"];
                    $cat["NAME"] = $res[$i]["NAME"];
                    $cat["URL"] = $res[$i]["URL"];
                    $cat["VIS"] = $res[$i]["VIS"];
                    $cats[$cat["PID"]][] = $cat;
                    $i++;
                }
            }
            if (isset($cats)) {
                $ret = '';
                if ($find == 'empty') {
                    $ret = ($this->manage->info_manuals_build_tree($cats, 0));
                    $ret .= ($this->manage->info_manuals_build_tree($cats, ''));
                } else {
                    $prefs_clean = array_unique($prefs);
                    foreach ($prefs_clean as $key => $value) {
                        $ret .= $this->manage->info_manuals_build_tree($cats, $value);
                    }
                }
            } else {
                $ret = "<ul><li>" . order_ref_no_find_data . "</li></ul>";
            }
            die($ret);
        } else {
            die("Error!");
        }
    }

    //temp may be
    function ref_temp_add_post()
    {
        $this->access = new Access_Model();
        $this->manage = new Manage_Model();
        $this->db_ora = new DB_ora_Model();
        $this->access->checkAccess();
        if ($_POST) {
            $postname = (isset($_POST['postname']) && !empty($_POST['postname'])) ? $_POST['postname'] : die('error');
            $sql = <<<SQL
                    SELECT 
                        (DOLJ_SEQ.nextval+1) gg
                    FROM dual
SQL;

            if ($res = $this->db_ora->go_result_once($sql)) {
                $id = $res['GG'];
                $sql = <<<SQL
                    insert 
                        into DOLJNOST
                        (TEXT)
                        VALUES
                        ('$postname')
SQL;

                $this->db_ora->go_query($sql);
                die($id);

            }
            }

    }

}
?>