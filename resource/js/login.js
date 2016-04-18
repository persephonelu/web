// JavaScript Document
//定义全局变量
if (typeof(base_url) == "undefined")
{
	var base_url = "http://git.appbk.com/";
}

//关键词控制器
 function controller($scope, $http)
 {
	 $scope.base_url = base_url;
	 $scope.login = function()
	 {
		email = $scope.email;
		password = $scope.password;

		//从服务器端获取登陆信息是否正确的信息
		url = base_url + 'user/check_user_login_input?email=' + email + "&password=" + password;

		$http.get(url).success(function(data)
      	{
			if (data.status==0) //如果正确
			{
				$scope.error_message = "";
				
				//跳转到用户app页面
				url = base_url + "main/user_app";
				window.location.href = url;

			}
			else
			{
				//展示错误页面
				$scope.error_message = data.message;
			}
		});
		
		
	 } 
 }