<?php
/**
 * 代码测试工具箱【控制器：视图方法】
 * @brief Site
 * @class Site
 * @note
 */
class Test extends IController implements adminAuthorization
{

    protected $checkRight = 'all';

    //模板测试
	function index()
	{
        $clientType = ITime::getNow();
	    $this->time = "2018-02-02";
        $this->redirect("index");
    }

    //调用自定义接口测试
    function getInterface(){
        $test = new TestApi();
        $test_rst = $test->index();
        var_dump($test_rst);
    }

    //数据库读写
    function getGoodsInfo(){
        $goods = new IQuery('goods');
        $goods->where = "id = 2";
        $goods_list = $goods->find();
        var_dump($goods_list);
    }

    //数据库写入操作
    function setGoodsInfo(){
        $goods = new IModel("goods");
        $goods->setData(array("name"=>"测试写入数据库"));
        $add_res = $goods->add();
        var_dump($add_res);
    }

    //数据库删除操作
    function delGoodsInfo(){
        $goods = new IModel("goods");
        $del_res = $goods->del("id = 4");
        var_dump($del_res);
    }

    //原生sql
    function rogin(){
        $sql_res =  IDBFactory::getDB()->query("select * from iwebshop_order");
        var_dump($sql_res);
    }

    /**
     * api接口调试：内部【query】
     * Api::run("getGoodsInfo",array("id"=>2));
     * Api::run("getArtList",2);//整形
     * Api::run("getArtList","sort -");//排序
     */
    function getApiInQuery(){
        $api = Api::run("getGoodsInfo",array("id"=>2));
        var_dump($api);
    }

    /**
     * api接口调试：内部【file】
     */
    function getApiInFile(){
        $api = Api::run("getGoodsList",2);
        var_dump($api);
    }

    /**
     * api接口调试：外部【安卓，ios，h5，小程序】
     */
    function getApiOut(){
        //请求参数列表
        $param = array(
            'method' => 'getGoodsList',
            'time' => time(),
            'rand' => rand(1000000,99999999),
            'param' => array(2,3)
        );
        //接口签名
        $param['sign'] = $this->sign($param);
        $url = "http://www.manman.com/index.php?controller=service&action=api&".http_build_query($param);
        //执行
        var_dump(file_get_contents($url));
    }

    /**
     * @brief 加密算法
     * @param array $param 加密的数据
     * @return string
     */
    private function sign($param)
    {
        $key = "d41d8cd98f00b204e9800998ecf8427e";//通讯密钥
        ksort($param);
        reset($param);
        return md5(http_build_query($param).$key);
    }
}