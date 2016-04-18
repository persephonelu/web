====================当前版本信息====================
当前版本：Beta1.0.0

发布日期：2013-11-22

====================================================

====================修改历史====================


================================================

发布介绍:

此客户端库,目前支持百度商业推广API V3版本功能，以及部分百度商业推广API V2版本功能。

本客户端库旨在提供百度商业推广API以Json方式调用封装以及代理类生成，并不提供客户端业务逻辑。

使用过程中如果遇到任何问题，请联系apihelp@baidu.com并在标题注明《Baidu-API SDK使用过程问题求助》

注意事项:


1. 如何初始化Service
	使用者获取具体的service对象方法是：
	require_once '../sms_v3_AccountService.php';
	$service = new sms_v3_AccountService();
	此时会从CommonService.php里加载默认用户信息，包括url、username、password、token、target、accessToken等。
	若想重新设置这些参数，可以执行以下方法：
	//重设url
	$service->setServiceurl("yoururl")；
	//重设用户信息
	$newheader = new AuthHeader();
	$newheader->setUsername("yourname");
	$newheader->setPassword("yourpwd");
	$newheader->setToken("yourtoken");
	$newheader->setTarget("yourtarget");
	$service->setAuthHeader($newheader);
	说明：使用者在不使用accesstoken时不要对其配置；在使用accesstoken时请确保username和password不为空（错误的username和pwd也可以，但是不能为空）

2. 如何调用API进行请求？
	使用获取的具体service对象调用其指定方法，参数为要请求方法的Request类，如
	$inforequest = new updateAccountInfoRequest ( );
	$accountInfoType = new AccountInfoType ( );
	$ips = array ("192.168.2.3", "192.168.2.6" );
	$accountInfoType->setExcludeIp ( $ips );
	$inforequest->setAccountInfoType ( $accountInfoType );
	//可以指定底层协议为json或soap
	$service->setIsJson(true);
	//执行调用
	$jsonEnv = $service->updateAccountInfo ( $inforequest );

3. 如何取得调用结果 ?
	如步骤2，$jsonEnv 记录了本次请求返回的数据。
	若想获得本次请求的状态信息，可以如下调用：
	//json方式调用
	$resheader = $service->getJsonHeader();
	//soap方式调用
	$resheader = $service->getSoapHeader();
	//获取原始json字串
	$resheader = $service->getJsonStr();
	$resheader中记录本次请求的状态，如失败，失败信息记录在failures属性中。


