// JavaScript Document
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
var rank_app  = angular.module('rank_app', ['ngRoute']);

//路由配置
rank_app.config(['$routeProvider',function ($routeProvider) {
      $routeProvider
      .when('/', {
        templateUrl:template_url+'app_rank.html',
        controller:'app_rank_controller'
      })
	  .when('/word_rank', {
        templateUrl:template_url+'word_rank.html',
        controller:'word_rank_controller'
      })
	  .when('/tag_rank', { //用户兴趣标签排行榜
        templateUrl:template_url+'tag_rank.html',
        controller:'tag_rank_controller'
      })
	  .when('/app_search/:name', {
        templateUrl:template_url+'app_search.html',
        controller:'app_search_controller'
      })	  
	  .when('/app_content/:app_id', { //app内容页面
        templateUrl:template_url+'app_content.html',
        controller:'app_content_controller'
      })
	  .when('/word_search/:name', {
        templateUrl:template_url+'word_search.html',
        controller:'word_search_controller'
      })
      .otherwise({
        redirectTo: '/'
      });
}]);


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
	
}


//app内容页控制器
function app_content_controller($scope, $http,$routeParams)
{
	//接收参数
	app_id = $routeParams.app_id;
	
	//获得app基本信息
	url = base_url + 'app/get_app_info?app_id=' + app_id; 
	$http.get(url).success(function(data)
	{
		$scope.app_info = data;
	});
	
	//获得app的排名趋势变化数据
	url = base_url + 'app/get_app_rank_trend?app_id=' + app_id; 
	$http.get(url).success(function(data)
	{
		//展示到图标上
		$('#trend').highcharts(data); 
	});
}

//tag_rank的控制器
function tag_rank_controller($scope, $http)
{
	//获得一级类别信息
	url = base_url + 'app/get_categories';
	$http.get(url).success(function(data)
	{
		$scope.categories = data;
	});
		
	//获得游戏二级类别信息
	url = base_url + 'app/get_game_categories';
	$http.get(url).success(function(data)
	{
		$scope.game_categories = data;
	});
	
	//只要30个，不翻页
	$scope.category_selected = "天气";
	get_tag_rank($scope.category_selected);
	
	//点击时，获得某个类别下的app排行
	$scope.get_category_tag_rank = function(category)
	{
		get_tag_rank(category);
	}
	
	function get_tag_rank(category)
	{
		$scope.category_selected = category;
		$scope.tag_rank = {};//先清空数据
		$scope.tag_rank_show_wait = true;
		
		url = base_url + "app_weibo/get_tag_rank?c=" + category ;
		$http.get(url).success(function(data)
		{
			$scope.tag_rank = data;
			$scope.tag_rank_show_wait = false;
		});
	}
	
}


//word_rank的控制器
function word_rank_controller($scope, $http, $location)
{
	//获得一级类别信息
	url = base_url + 'app/get_categories';
	$http.get(url).success(function(data)
	{
		$scope.categories = data;
	});
		
	//获得游戏二级类别信息
	url = base_url + 'app/get_game_categories';
	$http.get(url).success(function(data)
	{
		$scope.game_categories = data;
	});
	
	//只要30个，不翻页
	$scope.category_selected = "天气";
	get_word_rank($scope.category_selected);
	
	//点击时，获得某个类别下的app排行
	$scope.get_category_word_rank = function(category)
	{
		get_word_rank(category);
	}
	
	function get_word_rank(category)
	{
		$scope.category_selected = category;
		$scope.word_rank = {};//先清空数据
		$scope.word_rank_show_wait = true;
		
		
		//考虑到异步，需要分开来写
		url = base_url + "word/get_word_rank?c=" + category +  "&start=0&limit=30";
		$http.get(url).success(function(data)
		{
			$scope.word_rank = data.results;
			$scope.word_rank_show_wait = false;
		});
	}
	
	//word search 搜索
	$scope.word_search = function()
	{
		name = $scope.name;
		$location.path("word_search/" + name);
	}
	
	
}

//word search控制器
function word_search_controller($scope, $http, $routeParams)
{
	
	//接收参数，获得搜索结果
	name = $routeParams.name;
	get_word_search_results(name);
	
	//点击，获得搜索app结果
	$scope.search_word = function()
	{
		name = $scope.name;
		get_word_search_results(name);
	}
	
	function get_word_search_results(name)
	{
		$scope.app_search_show_wait = true;
		$scope.word_search_results  = {};
		url = base_url + 'word/get_word_search_results?n=' + name;
		$http.get(url).success(function(data)
		{
			$scope.word_search_results = data;
			if ( data.length == 0 ) //如果没有搜索结果
			{
				$scope.word_search_results.splice(0,0,{"word":"没有相关结果"});
			}
			$scope.app_search_show_wait = false;
		});
	}
}

//app search控制器
function app_search_controller($scope, $http, $routeParams)
{
	
	//接收参数，获得搜索结果
	name = $routeParams.name;
	get_app_search_results(name);
	
	//点击，获得搜索app结果
	$scope.search_app = function()
	{
		name = $scope.name;
		get_app_search_results(name);
	}
	
	function get_app_search_results(name)
	{
		url = base_url + 'app/get_app_search_results?n=' + name;
		$http.get(url).success(function(data)
		{
			$scope.app_search_results = data.results;
			if ( data.results.length == 0 ) //如果没有搜索结果
			{
				$scope.app_search_results.splice(0,0,{"name":"没有相关结果"});
			}
		});
	}
}



//app_rank的控制器
function app_rank_controller($scope, $http, $location)
{
	//获得一级类别信息
	url = base_url + 'app/get_categories';
	$http.get(url).success(function(data)
	{
		$scope.categories = data;
	});
		
	//获得游戏二级类别信息
	url = base_url + 'app/get_game_categories';
	$http.get(url).success(function(data)
	{
		$scope.game_categories = data;
	});
	
	//获得每个类型的app top 排行榜，只要30个，不翻页
	$scope.category_selected = "应用"; 
	get_app_rank( $scope.category_selected );
	
	//获得某个类别下的app排行
	$scope.get_category_app_rank = function(category)
	{
		get_app_rank(category);
	}
	
	//获得app 排行信息
	function get_app_rank(category)
	{
		$scope.category_selected = category;
		$scope.app_rank_show_wait = true;
		rank_type = ["topfreeapplications","toppaidapplications","topgrossingapplications"];//榜单类型
		//获得每个榜单的内容
		$scope.app_rank = {};
		
		//考虑到异步，需要分开来写
		url = base_url + "app/get_app_rank?c=" + category + "&rank_type=" + rank_type[0] + "&start=0&limit=30";
		$http.get(url).success(function(data)
		{
			$scope.app_rank[ rank_type[0] ] = data.results;
			$scope.app_rank_show_wait = false;
		});
		
		url = base_url + "app/get_app_rank?c=" + category + "&rank_type=" + rank_type[1] + "&start=0&limit=30";
		$http.get(url).success(function(data)
		{
			$scope.app_rank[ rank_type[1] ] = data.results;
			$scope.app_rank_show_wait = false;
		});
		
		url = base_url + "app/get_app_rank?c=" + category + "&rank_type=" + rank_type[2] + "&start=0&limit=30";
		$http.get(url).success(function(data)
		{
			$scope.app_rank[ rank_type[2] ] = data.results;
			$scope.app_rank_show_wait = false;
		});
	}
	
	//app search 搜索
	$scope.app_search = function()
	{
		name = $scope.name;
		$location.path("app_search/" + name);
	}
	
}