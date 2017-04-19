<?php

/**
 * GameController.class.php UTF-8
 * app游戏管理
 * @date: 2016年9月2日下午11:01:47
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
namespace Newapp\Controller;

use Common\Controller\AdminbaseController;

class GameController extends AdminbaseController
{
    protected $game_model;
    protected $gc_model;
    protected $gv_model;
    protected $term_relationships_model;
    protected $terms_model;
    protected $where;
    function _initialize() {
        parent::_initialize();
        $this->game_model = D("Common/Game");
        $this->gc_model = M('game_client');
        $this->gv_model = M('game_version');
        $this->where = 1;
    }
    function index() {
        $this->gList();
        $this->gameStatus();
        $this->gtype(0);
        $this->gclassify();
        $this->display();
    }
    public function gList($status=2) {
        $this->_game(true,'',2);
        $where_ands = array();
        array_push($where_ands, "g.is_delete  = ".$status);
        array_push($where_ands, "g.is_app  = 2");
        $fields = array(
            'gstatus' => array(
                "field" => "g.is_app",
                "operator" => "="
            ),
            'gtype' => array(
                "field" => "gt.type_id",
                "operator" => "="
            ),
            'appid' => array(
                "field" => "g.id",
                "operator" => "="
            ),
            'gclassify' => array(
                "field" => "g.classify",
                "operator" => "="
            )
        );
    
        if (IS_POST) {
            foreach ($fields as $param => $val) {
                if (isset($_POST[$param]) && !empty($_POST[$param])) {
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = trim($_POST[$param]);
                    $_GET[$param] = $get;
    
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        } else {
            foreach ($fields as $param => $val) {
                if (isset($_GET[$param]) && !empty($_GET[$param])) {
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = trim($_GET[$param]);
    
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        }
        array_push($where_ands, $this->where);
    
        $where = join(" AND ", $where_ands);
    
        /* 类型为空时，直接查询game表即可 */
        if (empty($_GET['gtype'])) {
            $count = $this->game_model->alias("g")->where($where)->count();
    
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
            $page = $this->page($count, $rows);
            $items = $this->game_model->alias("g")->where($where)->limit($page->firstRow . ',' . $page->listRows)->order(
                    'g.listorder desc , g.id desc')->select();
        } else {
            $count = $this->game_model->alias("g")->join(
                    "left join " . C('DB_PREFIX') . "game_gt gt ON g.id = gt.app_id")->where($where)->order('id desc')->count();
    
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
            $page = $this->page($count, $rows);
    
            $items = $this->game_model->alias("g")->join(
                    "left join " . C('DB_PREFIX') . "game_gt gt ON g.id = gt.app_id")->where($where)->limit(
                            $page->firstRow . ',' . $page->listRows)->order('g.listorder desc, g.id desc')->select();
        }
    
        $this->assign("appgames", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    function add() {
        $this->gtype(1);
        $this->gameStatus(1);
        $this->display();
    }
    function add_post() {
        if (IS_POST) {
            /* 获取POST数据 */
            $game_data['name'] = I('post.gamename/s');  //游戏名称
            $game_data['category'] = I('post.gcategory/d');  //单机网游
            $game_data['classify'] = I('post.gclassify/d');  //游戏类别
            $game_data['is_hot'] = I('post.hot/d',0);  //游戏热门程度
            $game_data['listorder'] = I('post.listorder/d',0);  //游戏热门程度
            $gametypes = I('post.gtype');  //游戏标签
            $info_data['publicity'] = I('post.oneword/s');  //一句话描述
            $info_data['description'] = I('post.description');  //游戏详细描述
            $info_data['androidurl'] = I('post.androidurl/s');  //安卓版下载地址 
            $info_data['adxt'] = I('post.adxt/s');  //安卓版适用系统
            $info_data['size'] = I('post.size/s');  //游戏大小
            $info_data['lang'] = I('post.lang/s','中文');  //游戏语言
            $ext_data['down_cnt'] = I('post.count/d',0); //下载次数
//             $game_data['status'] = I('post.gstatus/d',1);  //当前状态
            $game_data['is_app'] = 1;  //app游戏
            $game_data['is_own'] = 1;  //app游戏
            $game_data['create_time'] = time();
            $game_data['update_time'] = $game_data['create_time'];
            $version   = I('post.version/s','');
            $game_data['mobile_icon'] = I('post.logo');
            
            $photourl = I('post.photos_url');
            $photoalt = I('post.photos_alt');
            if (!empty($photoalt) && !empty($photourl)){
                foreach ($photourl as $key => $url) {
                    $photourl = $url;
                    $imagearr[] = array(
                        "url" => $photourl,
                        "alt" => $photoalt[$key]
                    );
                }
            }
            
            $info_data['image']=json_encode($imagearr);
            /* 检测输入参数合法性, 游戏名 */
            if (empty($game_data['name'])) {
                $this->error("游戏名为空，请填写游戏名");
                exit();
            }
            
            /* 检测输入参数合法性, 游戏标签 */
            if (empty($gametypes)) {
                $this->error("请填写游戏标签!");
                exit();
            } else {
                $game_data['type'] = implode(',', $gametypes);
            }
            
            /* 检测输入参数合法性,游戏简介 */
            if (empty($info_data['publicity'])) {
                $this->error("请填写一句话描述");
                exit();
            }
            
            /* 检测输入参数合法性,游戏描述 */
            if (empty($info_data['description'])) {
                $this->error("请填写游戏描述");
                exit();
            }
            
            /* 检测输入参数合法性, 游戏版本  */
            if (empty($version)) {
                $this->error("游戏版本为空，请填写游戏版本");
            }else{
                $checkExpressions = "/^\d+(\.\d+){0,2}$/";
                $len = strlen($version);
                if ($len>10 || false == preg_match($checkExpressions, $version)){
                    $this->error("游戏版本号填写错误，数字与小数点组合");
                }
            }
            
            /* 上线时间 */
//             if (2 == $game_data['status']) {
//                 $game_data['run_time'] = $game_data['update_time'];
//             }
            
            // 获取游戏名称拼音
            import('Vendor.Pin');
            $pin = new \Pin();
            $game_data['pinyin'] = $pin->pinyin($game_data['name']);
            $game_data['initial'] = $pin->pinyin($game_data['name'], true);

            if ($this->game_model->create($game_data)) {
                $app_id = $this->game_model->add();
                /* 插入游戏类型 */
                if ($app_id > 0) {
                    $game_data['app_key'] = md5(rand(1, 90000) . md5($gamedata['pinyin']).uniqid() . '##');
                    $game_data['initial'] = $game_data['initial'].'_'.$app_id;
                    $game_data['id'] = $app_id;
                    $this->game_model->save($game_data);
                    
                    //游戏版本插入
                    $gv_data['app_id'] = $app_id;
                    $gv_data['version'] = $version;
                    $gv_data['create_time'] = $game_data['create_time'];                    
                    $gv_id = $this->gv_model->add($gv_data);
                    
                    //client_id 操作
                    $gc_data['app_id'] = $app_id;
                    $gc_data['version'] = $version;
                    $gc_data['client_key'] = md5($version.md5($game_data['app_key'].$game_data['initial'].rand(10,1000)));
                    $gc_data['gv_id'] = $gv_id;
                    $gc_data['gv_new_id'] = $gv_id;
                    $this->gc_model->add($gc_data);
                    
                    //game_ext插入
                    $ext_model = M('game_ext');
                    $ext_data['app_id'] = $app_id;
                    $ext_model->add($ext_data);
                    
                    
                    //game_info 插入 
                    $info_model = M('game_info');
                    $info_data['app_id'] = $app_id;
//                     $info_data['mobile_icon'] = $game_data['icon'];
                    $info_model->add($info_data);
                    
                    //游戏标签
                    foreach ($gametypes as $k => $val) {
                        $type_data[$k]['app_id'] = $app_id;
                        $type_data[$k]['type_id'] = $val;
                    }
                    
                    $gtmodel = M('game_gt');
                    $gtmodel->addAll($type_data);
                    $this->success("添加成功！", U("Game/index"));
                }
            } else {
                $this->error($this->game_model->getError());
            }
            exit();
        }
        $this->error('页面不存在');
    }
    public function edit() {
        $app_id = intval(I('get.id'));
        if ($app_id > 0) {
            $this->gtype(1);
            $this->gameStatus(1);
            
            $gamedata = $this->game_model->join($join)->where(array(
                'id' => $app_id 
            ))->find();
            $infodata = M('game_info')->where(array(
                'app_id' => $app_id
            ))->find();
            $extdata = M('game_ext')->where(array(
                'app_id' => $app_id 
            ))->find();
            
            $verdata = $this->gv_model->where(array(
                'app_id' => $app_id,
                'status' => 2
            ))->order('id desc')->find();
/*             $gt_model = M('game_gt');
            $type_ids = $gt_model->where(array(
                "app_id" => $app_id 
            ))->getField("type_id", true); */
            
            $type_ids = explode(',', $gamedata['type']);
            $this->assign("type_ids", $type_ids);
            $this->assign('game', $gamedata);
            $this->assign('gameinfo', $infodata);
            $this->assign('extdata', $extdata);
            $this->assign('verdata', $verdata);
            $this->assign("smeta",json_decode($infodata['image'],true));
            $this->display();
        } else {
            $this->error("参数错误");
        }
    }
    public function edit_post() {
        if (IS_POST) {
            $app_id = I('post.gameid/d',0);
            if(empty($app_id)){
                $this->error("参数错误！");
            }
            
            $ver_id = I('post.verid/d',0);
            /* 获取POST数据 */
            $game_data['id'] = $app_id;  //游戏名称
            $game_data['name'] = I('post.gamename/s');  //游戏名称
            $game_data['category'] = I('post.gcategory/d');  //单机网游
            $game_data['classify'] = I('post.gclassify/d');  //游戏类别
            $game_data['is_hot'] = I('post.hot/d',0);  //游戏热门程度
            $game_data['listorder'] = I('post.listorder/d',0);  //游戏热门程度
            $gametypes = I('post.gtype');  //游戏标签
            $info_data['publicity'] = I('post.oneword/s');  //一句话描述
            $info_data['description'] = I('post.description');  //游戏详细描述
            $info_data['androidurl'] = I('post.androidurl/s');  //安卓版下载地址 
            $info_data['adxt'] = I('post.adxt/s');  //安卓版适用系统
            $info_data['size'] = I('post.size/s');  //游戏大小
            $info_data['lang'] = I('post.lang/s','中文');  //游戏语言
            $ext_data['down_cnt'] = I('post.count/d',0); //下载次数
//             $game_data['status'] = I('post.gstatus/d');  //当前状态
//             $game_data['is_app'] = 2;  //app游戏
            // $game_data['is_own'] = 1;  //app游戏
            $game_data['update_time'] =  time();
            $version   = I('post.version/s','');
            $game_data['mobile_icon'] = I('post.logo');
            
            $photourl = I('post.photos_url');
            $photoalt = I('post.photos_alt');
            if (!empty($photoalt) && !empty($photourl)){
                foreach ($photourl as $key => $url) {
                    $photourl = $url;
                    $imagearr[] = array(
                        "url" => $photourl,
                        "alt" => $photoalt[$key]
                    );
                }
            }
            
            $info_data['image']=json_encode($imagearr);
            /* 检测输入参数合法性, 游戏名 */
            if (empty($game_data['name'])) {
                $this->error("游戏名为空，请填写游戏名");
                exit();
            }
            
            /* 检测输入参数合法性, 游戏标签 */
            if (empty($gametypes)) {
                $this->error("请填写游戏标签!");
                exit();
            } else {
                $game_data['type'] = implode(',', $gametypes);
            }
            
            /* 检测输入参数合法性,游戏简介 */
            if (empty($info_data['publicity'])) {
                $this->error("请填写一句话描述");
                exit();
            }
            
            /* 检测输入参数合法性,游戏描述 */
            if (empty($info_data['description'])) {
                $this->error("请填写游戏描述");
                exit();
            }
            
            /* 检测输入参数合法性, 游戏版本  */
            if (empty($version)) {
                $this->error("游戏版本为空，请填写游戏版本");
            }else{
                $checkExpressions = "/^\d+(\.\d+){0,2}$/";
                $len = strlen($version);
                if ($len>10 || false == preg_match($checkExpressions, $version)){
                    $this->error("游戏版本号填写错误，数字与小数点组合");
                }
            }
            
            /* 上线时间 */
//             if (2 == $game_data['status']) {
//                 $game_data['run_time'] = $game_data['update_time'];
//             }
            
            // 获取游戏名称拼音
//             import('Vendor.Pin');
//             $pin = new \Pin();
//             $game_data['pinyin'] = $pin->pinyin($game_data['name']);
//             $game_data['initial'] = $pin->pinyin($game_data['name'], true);
//             $game_data['initial'] = $game_data['initial'].'_'.$app_id;
            
            if ($this->game_model->create($game_data)) {
                $rs = $this->game_model->save();
                /* 插入游戏类型 */
                if ($rs!==false) {
                    
                    $gv_data = $this->gv_model->where(array('id'=>$ver_id))->find();
                    if (empty($gv_data)){
                        $gv_data['app_id'] = $app_id;
                        $gv_data['version'] = $version;
                        $gv_data['create_time'] = time();
                        $gv_data['update_time'] = $gv_data['create_time'];
                        $gv_id = $this->gv_model->add($gv_data);
                    }else{
                        //游戏版本保存
                        $gv_data['app_id'] = $app_id;
                        $gv_data['id'] = $ver_id;
                        $gv_data['version'] = $version;
                        $gv_data['update_time'] = $game_data['update_time'];
                        $this->gv_model->save($gv_data);
                    }
                    
                    
                    $ext_data['app_id'] = $app_id;
                    //game_ext保存
                    $ext_model = M('game_ext');
                    $gext_data = $ext_model->where(array('app_id'=>$app_id))->find();
                    if (empty($gext_data)){
                        $ext_model->add($ext_data);
                    }else{
                        $ext_model->save($ext_data);
                    }
                   
                    
                    //game_info 保存 
                    $info_model = M('game_info');
                    $info_data['app_id'] = $app_id;
//                     $info_data['mobile_icon'] = $game_data['icon'];
                    $ginfo_data = $info_model->where(array('app_id'=>$app_id))->find();
                    if (empty($ginfo_data)){
                        $info_model->add($info_data);
                    } else {
                        $info_model->save($info_data);
                    }
                    
                    //游戏标签
                    foreach ($gametypes as $k => $val) {
                        $type_data[$k]['app_id'] = $app_id;
                        $type_data[$k]['type_id'] = $val;
                    }
                    
                    $gtmodel = M('game_gt');
                    $gtmodel->where(array(
                        'app_id' => $game_data['id']
                    ))->delete();
                    $gtmodel->addAll($type_data);
                    $this->success("编辑成功！", U("Game/index"));
                }
            } else {
                $this->error($this->game_model->getError());
            }
            $this->success("编辑失败！");
            exit();
        }
        $this->error('页面不存在');
    }
    
    // 排序
    public function listorders() {        
        $status = parent::_listorders($this->game_model);
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
    private function _getTree() {
        $term_id = empty($_REQUEST['term']) ? 0 : intval($_REQUEST['term']);
        $result = $this->terms_model->order(array(
            "listorder" => "asc" 
        ))->select();
        
        $tree = new \Tree();
        $tree->icon = array(
            '&nbsp;&nbsp;&nbsp;│ ', 
            '&nbsp;&nbsp;&nbsp;├─ ', 
            '&nbsp;&nbsp;&nbsp;└─ ' 
        );
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        foreach ($result as $r) {
            $r['str_manage'] = '<a href="' . U("AdminTerm/add", array(
                "parent" => $r['term_id'] 
            )) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array(
                "id" => $r['term_id'] 
            )) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array(
                "id" => $r['term_id'] 
            )) . '">删除</a> ';
            $r['visit'] = "<a href='#'>访问</a>";
            $r['taxonomys'] = $this->taxonomys[$r['taxonomy']];
            $r['id'] = $r['term_id'];
            $r['parentid'] = $r['parent'];
            $r['selected'] = $term_id == $r['term_id'] ? "selected" : "";
            $array[] = $r;
        }
        
        $tree->init($array);
        $str = "<option value='\$id' \$selected>\$spacer\$name</option>";
        $taxonomys = $tree->get_tree(0, $str);
        $this->assign("taxonomys", $taxonomys);
    }
    
    function delete() {
        if (isset($_GET['id'])) {
            $app_id = intval(I("get.id"));
            $data['is_app'] = 1;
            if ($this->game_model->where("id=$app_id")->save($data)) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
        if (isset($_POST['ids'])) {
            $app_ids = join(",", $_POST['ids']);
            $data['is_app'] =3;
            if ($this->term_relationships_model->where("id in ($app_ids)")->save($data)) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }
    
    function check() {
        if (isset($_POST['ids']) && $_GET["online"]) {
            $data["is_app"] = 2;
            $ids = $_POST['ids'];
            foreach ($ids as $v){
                $id = $v;
                $packageurl = M('game_info')->where(array('app_id'=>$id))->getField('androidurl');
                if (empty($packageurl)){
                    $this->error("请上传母包,gameid".$id);
                }
            }
            $app_ids = join(",", $_POST['ids']);            
            if ($this->game_model->where("id in ($app_ids)")->save($data) !== false) {
                $this->success("上线成功！");
            } else {
                $this->error("上线失败！");
            }
        }
        if (isset($_POST['ids']) && $_GET["offline"]) {
            $data["is_app"] = 3;
            $app_ids = join(",", $_POST['ids']);
            if ($this->game_model->where("id in ($app_ids)")->save($data) !== false) {
                $this->success("游戏下线成功！");
            } else {
                $this->error("游戏下线失败！");
            }
        }
    }
    
    function top() {
        if (isset($_POST['ids']) && $_GET["hot"]) {
            $data["is_hot"] = 1;
            
            $app_ids = join(",", $_POST['ids']);
            if ($this->game_model->where("id in ($app_ids)")->save($data) !== false) {
                $this->success("设置热门成功！");
            } else {
                $this->error("设置热门失败！");
            }
        }
        if (isset($_POST['ids']) && $_GET["unhot"]) {
            $data["is_hot"] = 0;
            $app_ids = join(",", $_POST['ids']);
            if ($this->game_model->where("id in ($app_ids)")->save($data) !== false) {
                $this->success("设置非热门成功！");
            } else {
                $this->error("设置非热门失败！");
            }
        }
    }
    
    function restore() {
        if (isset($_GET['id'])) {
            $id = intval(I("get.id"));
            $data = array(
                "id" => $id, 
                "is_app" => "2" 
            );
            if ($this->game_model->save($data)) {
                $this->success("还原成功！");
            } else {
                $this->error("还原失败！");
            }
        }
    }
    
    public function gameStatus($option = NULL) {
        $gamestatus = array(
            '0' => "选择状态",
            '1' => "程序接入",
            '2' => "上线",
            '3' => "下线"
        );
    
        if (1 == $option) {
            $gamestatus = array(
                '1' => "程序接入",
                '2' => "上线",
                '3' => "下线"
            );
        }
        $this->assign("gamestatus", $gamestatus);
        return;
    }
    public function gclassify() {
        $gclassify = array(
            '0' => "全部",
            '1' => "网游",
            '2' => "单机"
        )
        ;
        $this->assign("gclassify", $gclassify);
        return;
    }
    
    /**
     * 游戏类型
     * @date: 2016年4月8日下午6:05:37
     *
     * @param option 参数选项
     * @return NULL
     * @since 1.0
     */
    public function gtype($option = NULL) {
        $cates = array(
            0 => "全部类型"
        );
    
        $typedata = M('game_type')->where(array(
            'status' => 2
        ))->getfield('id, name');
    
        if (1 == $option) {
            $this->assign('gtypes', $typedata);
            return;
        }
    
        if (!empty($typedata)) {
            $cates = $cates + $typedata;
        }
    
        $this->assign('gtypes', $cates);
        return;
    }
}