// JavaScript Document
//定义全局变量
//子页面
if (typeof(base_url) == "undefined")//如果在本地
{
	var template_url = "";
}
else
{
	//如果在服务器已经部署
	var template_url = base_url + 'resource/template/';
}

//定义全局变量
if (typeof(base_url) == "undefined")
{
	var base_url = "http://git.appbk.com/";
}

if (typeof(email) == "undefined")
{
	var email = "58100533@qq.com";
}

//服务模块
var user_app  = angular.module('user_app', ['ngRoute']);

//路由配置
user_app.config(['$routeProvider',function ($routeProvider) {
      $routeProvider
      .when('/', {
        templateUrl:template_url+'user_app_manage.html',
        controller:'user_app_manage_controller'
      })
      .otherwise({
        redirectTo: '/'
      });
}]);


//主页面的controller
function main_controller($scope, $http)
{	
	$scope.base_url = base_url;
	//获得用户信息
	url = base_url + 'user/get_user_info?email=' + email;
	$http.get(url).success(function(data)
	{
		$scope.user_info = data;
	});

}


//app管理页面控制器
function user_app_manage_controller($scope, $http) 
{
	//加载提示
	$scope.user_app_show_wait = true;
	
	//tab菜单管理
	$scope.old_app_tab = true;
	$scope.new_app_tab = false;
	  
	//获得用户app列表
	$http.get(base_url + 'user_app/get_user_apps?email=' + email).success(function(data)
	{
		$scope.apps = data;
		$scope.user_app_show_wait = false;
	});
	
	//删除用户app
	$scope.del_user_app = function(index)
	{
		app_id = $scope.apps[index].app_id;
		//页面上删除对应的app
		$scope.apps.splice(index,1);
		
		//真实删除数据
		$http.get(base_url + 'user_app/del_user_app?email=' + email + '&app_id=' + app_id).success(function(data)
		{
		});
	}
	
	//搜索app
	$scope.search_app = function()
	{
		query = $scope.query;
		//获得搜索结果数据
		$http.get(base_url + 'app/get_app_search_results?n=' + query).success(function(data)
		{
			$scope.app_search_results = data.results;
			if ( data.results.length == 0 ) //如果没有搜索结果
			{
				$scope.app_search_results.splice(0,0,{"name":"没有相关结果"});
			}
		});
	}
	
	//根据app_id添加app
	$scope.add_user_app = function(index)
	{
		//页面上添加对应的app
		$scope.apps.splice(index,0,$scope.app_search_results[index]);
		
		app_id = $scope.apps[index].app_id;
		
		//真实后台添加数据
		$http.get(base_url + 'user_app/add_user_app?email=' + email + '&app_id=' + app_id).success(function(data)
		{
		});
	}
	
	//tab点击
	//已提交app市场的tab
	$scope.old_app_tab_click = function()
	{
		$scope.old_app_tab = true;
		$scope.new_app_tab = false;
	}
	
	//未提交app市场的tab
	$scope.new_app_tab_click = function()
	{
		$scope.old_app_tab = false;
		$scope.new_app_tab = true;
		//加载app类别代码
		$http.get(base_url + 'app/get_categories').success(function(data)
		{
			$scope.categories = data;
		});
		
	}
	
	//添加未提交到市场上的app
	$scope.add_new_app = function ()
	{
		name = $scope.new_app_name;
		description = $scope.new_app_description;
		category = $scope.new_app_category;
		url = base_url + 'user_app/add_user_new_app?n=' + name + "&d=" + description + "&c=" + category + "&email=" + email;
		icon = "http://appbk.oss-cn-hangzhou.aliyuncs.com/images/57.png";
		
		//真实后台添加数据
		$http.get(url).success(function(data)
		{
			app_id = data.app_id;
			//页面上添加对应的app
			$scope.apps.splice(index,0,{"name":name,"ori_classes":category,"app_id":app_id,"icon":icon});
		});
		
		
	}
}

