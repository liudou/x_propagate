<?php
/**
 * Created by PhpStorm.
 * User: sks
 * Date: 2016/4/28
 * Time: 20:08
 */
use Core\Mall;
/**
 * @name IndexController
 * @author xuebingwang
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class PropagateController extends Mall {

    public function indexAction() {
        $articleid=I('articleid',1);
        $model = new ArticlePropagateModel();
        $tree = $model->getTree(0,['userid','name','parent_id'],$articleid);
        $this->assign('tree', json_encode($tree));
    }

    public function type2Action() {
        $articleid=I('articleid',1);
        $model = new ArticlePropagateModel();
        $tree = $model->getTree(0,['userid','name','parent_id'],$articleid);
        $this->assign('tree', json_encode($tree));
    }
    public function type3Action() {
        $articleid=I('articleid',1);
        $model = new ArticlePropagateModel();
        //$tree = $model->getByArticleid($articleid);

        $sql="select a.userid,a.name,a.parent_id ,COUNT(b.id) readnum from t_article_propagate a
left JOIN t_read_article b on b.original_userid=a.userid
where a.article_id=".$articleid." GROUP BY a.userid ";

        $tree = M()->query($sql)->fetchAll();
        $nodes=[];
        $links=[];
        $i=0;
        $user = new WxCustomerModel();
        $wxuser = $user->getUser($this->user['openid']);
        if(empty($wxuser)){
            $user->save(['openid'=>$this->user['openid']]);
            $wxuser = $user->getUser($this->user['openid']);
        }
        foreach($tree as $key=> $item){
            $nodes[$key]['category']=0;
            $nodes[$key]['name']=$item['userid'];
            $nodes[$key]['label']=$item['name'];
            $nodes[$key]['value']=$item['readnum'];
            if(!empty($item['parent_id'])){
                $links[$i]['source']=$item['userid'];
                $links[$i]['target']=$item['parent_id'];
                $links[$i]['weight']=2;
                $i=$i+1;
            }
            if($wxuser['userid']==$item['userid']){

            }
        }
        $this->assign('nodes', json_encode($nodes));
        $this->assign('links', json_encode($links));
    }
    public function type4Action() {

    }
}