// JavaScript Document
//定义全局变量
if (typeof(base_url) == "undefined")
{
	var base_url = "http://git.appbk.com/";
}

if (typeof(email) == "undefined")
{
	var email = "58100533@qq.com";
}

//主页面的controller
function main_controller($scope, $http)
{	
	//获得url信息
	$scope.base_url = base_url;
	//判断用户是否登陆
	if (email.length<1)//如果未登录
	{
		$scope.user_login_show = false;
		$scope.user_not_login_show = true;
	}
	else
	{
		$scope.user_not_login_show = false;
		//获取用户信息
		url = base_url + 'user/get_user_info?email=' + email;
		$http.get(url).success(function(data)
		{
			$scope.user_info = data;
			$scope.user_login_show = true;
		});

	}
	
	//app search 搜索
	$scope.app_search = function()
	{
		name = $scope.name;
		url = base_url + "main/rank#/app_search/" + name;
		window.location.href = url;
	}
	
}
