<?php
/**
 * Created by PhpStorm.
 * User: maris
 * Date: 2016/1/22
 * Time: 15:33
 */
//ping++ api
require_once("resource/pingpp/init.php");

//支付相关的model
class Pay_provider extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    //测试
    public function test($app_id)
    {
        //step 1，初始化
        \Pingpp\Pingpp::setApiKey('sk_test_Hi1enDvDervL1uTmPS0af1SO');//测试
        $order_no = substr(md5(time()), 0, 12); //自动生成

        //step 2,发起支付请求获取支付凭据
        $extra = array("success_url"=>"http://www.appbk.com/");
        $ch = \Pingpp\Charge::create(array(
            'order_no'  => $order_no, //商户订单号，适配每个渠道对此参数的要求，必须在商户系统内唯一
            'amount'    => '100', //订单总金额, 单位为对应币种的最小货币单位，例如：人民币为分
            'app'       => array('id' => 'app_X1mn5SvfTy94ffXb'),//ping++的app id
            'channel'   => 'alipay_pc_direct', //渠道，alipay_pc_direct:支付宝 PC 网页支付
            'currency'  => 'cny', //币种
            'client_ip' => '127.0.0.1', //客户端ip
            'subject'   => 'App运营助手，起飞包', //商品的标题，该参数最长为 32 个 Unicode 字符
            'body'      => 'App运营助手，起飞包，App首次上次相关服务', //商品的描述信息，该参数最长为 128 个 Unicode 字符
            'extra'     => $extra,
        ));

        return $ch; //json字符串
    }
}