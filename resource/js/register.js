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
	 $scope.register_user = function()
	 {
		email = $scope.email;
		password = $scope.password;
		password_check = $scope.password_check;
		//判断两个输入是否一致
		if ( password != password_check)
		{
			$scope.error_message = "两次输入的密码不一致，请检查后重新输入";
			return -1;
		}
		else
		{
			$scope.error_message = "";
		}
		
		//从服务器端获取注册是否争取的信息
		url = base_url + 'user/check_user_register_input?email=' + email;
		$http.get(url).success(function(data)
      	{
			if (data.status==0) //如果正确
			{
				$scope.error_message = "";
				//注册用户
				url = base_url + "user/reg_user?email=" + email + "&password=" + password;
				$http.get(url).success(function(data)
				{				
					//跳转到用户app页面
					url = base_url + "main/user_app";
					window.location.href = url;
				});

			}
			else
			{
				//展示错误页面
				$scope.error_message = data.message;
			}
		});
		
		
	 } 
 }
