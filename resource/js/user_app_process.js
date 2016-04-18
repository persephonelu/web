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

if (typeof(app_id) == "undefined")
{
	var app_id = "728200220";
	//var app_id = "790133739";
}


//服务模块
var user_app_process  = angular.module('user_app_process', ['ngRoute']);

//路由配置
user_app_process.config(['$routeProvider',function ($routeProvider) {
      $routeProvider
      .when('/', {
        templateUrl:template_url+'user_app_info.html',
        controller:'user_app_info_controller'
      })
	  .when('/user_app_keyword', {
        templateUrl:template_url+'user_app_keyword.html',
        controller:'user_app_keyword_controller'
      })
	  .when('/user_app_compete', {
        templateUrl:template_url+'user_app_compete.html',
        controller:'user_app_compete_controller'
      })
	  .when('/user_app_watch', {
        templateUrl:template_url+'user_app_watch.html',
        controller:'user_app_watch_controller'
      })
	  .when('/user_weibo', {
        templateUrl:template_url+'user_weibo.html',
        controller:'user_weibo_controller'
      })
	  .when('/user_weibo_profile', {
        templateUrl:template_url+'user_weibo_profile.html',
        controller:'user_weibo_profile_controller'
      })
      .otherwise({
        redirectTo: '/'
      });
}]);


//用户app关键词监控控制器
function user_app_watch_controller($scope, $http)
{

	//获得用户app关键词整体曝光度趋势图
	 watch_all_keywords();
	 
	//获得用户关键词的最新的热度和搜索排序位置信息
	url = base_url + 'user_app_keyword/get_app_keywords_rank_and_pos?app_id=' + app_id + '&email=' + email;
	$http.get(url).success(function(data)
	{
		//展示到图标上
		$scope.user_keywords_rank_and_pos = data;
	});
	
	//获得一个关键词的曝光度趋势变化数据
	$scope.get_keyword_trend = function($index)
	{
		word = $scope.user_keywords_rank_and_pos[$index].word;
		url = base_url + 'user_app_keyword/get_app_keyword_trend?app_id=' + app_id + '&n=' + word +'&email=' + email;
		$http.get(url).success(function(data)
		{
			//展示到图标上
			$('#trend').highcharts(data);
		});
	}
	
	$scope.watch_all_keywords = function ()
	{
		watch_all_keywords();
	}
	//获得用户app关键词整体曝光度趋势图
	function watch_all_keywords()
	{
		url = base_url + 'user_app_keyword/get_app_keywords_trend?app_id=' + app_id + '&email=' + email;
		$http.get(url).success(function(data)
		{
			//展示到图表
			$('#trend').highcharts(data); 
		});
	}
}

//用户基础信息控制器
function user_weibo_profile_controller($scope, $http)
{
	$scope.base_url = base_url;
	//获得性别分布
	url = base_url + 'app_weibo/get_app_user_gender?app_id=' + app_id;
	$http.get(url).success(function(data)
	{
		$('#user_gender').highcharts(data); 
	});
	
	//获得地域分布
	url = base_url + 'app_weibo/get_app_user_area?app_id=' + app_id;
	$http.get(url).success(function(data)
	{
		$('#user_area').highcharts(data); 
	});
	
	//获得用户上网时间分布
	url = base_url + 'app_weibo/get_app_user_time?app_id=' + app_id;
	$http.get(url).success(function(data)
	{
		$('#user_time').highcharts(data); 
	});
}

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
	
	//获得app基本信息
	url = base_url + 'app/get_app_info?app_id=' + app_id; 
	$http.get(url).success(function(data)
	{
		$scope.app_info = data;
	});
}

//用户微博控制器,微博标签
function user_weibo_controller($scope, $http)
{
	$scope.tags_show_wait = true;
	//获得app的用户标签
	url = base_url + 'app_weibo/get_app_user_tags?app_id=' + app_id;
	$http.get(url).success(function(data)
	{
		$scope.tags = data;
		$scope.tags_show_wait = false;
		if ( data.length == 0 ) //如果没有搜索结果
		{
			$scope.tags.splice(0,0,{"tag":"尚未添加竞品"});
		}
	});
}


//竞品管理控制器
function user_app_compete_controller($scope, $http)
{
	//加载提示
	$scope.user_app_show_wait = true;
	
	//tab菜单管理
	$scope.search_app_tab = true;
	$scope.sys_recommend_app_tab = false;
	$scope.user_also_buy_app_tab = false;
	
	//获得用户竞品app列表
	url = base_url + 'user_app/get_user_app_competes?email=' + email + "&app_id=" + app_id;
	$http.get(url).success(function(data)
	{
		$scope.apps = data;
		$scope.user_app_show_wait = false;
		if ( data.length == 0 ) //如果没有搜索结果
		{
			$scope.apps.splice(0,0,{"name":"尚未添加竞品"});
		}
	});
	
	//删除用户竞品
	$scope.del_compete_app = function(index)
	{
		compete_app_id = $scope.apps[index].app_id;
		//界面上删除
		$scope.apps.splice(index,1);
		//真实删除数据
		url = base_url + 'user_app/del_user_app_compete?email=' + email + "&app_id=" + app_id + "&compete_app_id=" + compete_app_id;
		$http.get(url).success(function(data)
		{
		});
	}
	
	//根据app_id添加app,根据搜索结果
	$scope.add_compete_app_by_search = function(index)
	{
		//页面上添加对应的app
		$scope.apps.splice(0,0,$scope.app_search_results[index]);
		compete_app_id = $scope.app_search_results[index].app_id;
		
		//真实后台添加数据
		url = base_url + 'user_app/add_user_app_compete?email=' + email + "&app_id=" + app_id + "&compete_app_id=" + compete_app_id;
		$http.get(url).success(function(data)
		{
		});
	}
	
	//根据app_id添加app,根据系统推荐
	$scope.add_compete_app_by_sys_recommend = function(index)
	{
		//页面上添加对应的app
		$scope.apps.splice(0,0,$scope.sys_recommend_apps[index]);
		compete_app_id = $scope.sys_recommend_apps[index].app_id;
		
		//真实后台添加数据
		url = base_url + 'user_app/add_user_app_compete?email=' + email + "&app_id=" + app_id + "&compete_app_id=" + compete_app_id;
		$http.get(url).success(function(data)
		{
		});
	}
	
	//根据app_id添加app,根据用户同时购买了
	$scope.add_compete_app_by_user_also_buy = function(index)
	{
		//页面上添加对应的app
		$scope.apps.splice(0,0,$scope.user_also_buy_apps[index]);
		compete_app_id = $scope.user_also_buy_apps[index].app_id;
		
		//真实后台添加数据
		url = base_url + 'user_app/add_user_app_compete?email=' + email + "&app_id=" + app_id + "&compete_app_id=" + compete_app_id;
		$http.get(url).success(function(data)
		{
		});
	}
	
	//tab 点击
	//搜索app tab
	$scope.search_app_tab_click = function()
	{
		//tab菜单管理
		$scope.search_app_tab = true;
		$scope.sys_recommend_app_tab = false;
		$scope.user_also_buy_app_tab = false;
	}
	
	//系统推荐
	$scope.sys_recommend_app_tab_click = function()
	{
		//tab菜单管理
		$scope.search_app_tab = false;
		$scope.sys_recommend_app_tab = true;
		$scope.user_also_buy_app_tab = false;
		$scope.sys_recommend_app_show_wait = true;
		
		url = base_url + 'app/get_app_relate_apps?app_id=' + app_id;
		$http.get(url).success(function(data)
		{
			$scope.sys_recommend_apps = data.results;
			if ( data.results.length == 0 ) //如果没有搜索结果
			{
				$scope.sys_recommend_apps.splice(0,0,{"name":"没有相关结果"});
			}
			$scope.sys_recommend_app_show_wait = false;
		});
	}
	
	//用户同时购买了
	$scope.user_also_buy_app_tab_click = function()
	{
		//tab菜单管理
		$scope.search_app_tab = false;
		$scope.sys_recommend_app_tab = false;
		$scope.user_also_buy_app_tab = true;
		$scope.user_also_buy_app_show_wait = true;
		
		url = base_url + 'app/get_app_user_also_buy_apps?app_id=' + app_id;
		$http.get(url).success(function(data)
		{
			$scope.user_also_buy_apps = data;
			if ( data.length == 0 ) //如果没有搜索结果
			{
				$scope.user_also_buy_apps.splice(0,0,{"name":"没有相关结果"});
			}
			$scope.user_also_buy_app_show_wait = false;
		});
	}
	
	
	//搜索app
	$scope.search_app = function()
	{
		$scope.app_search_show_wait = true;
		$scope.app_search_results = {};
		query = $scope.query;
		//获得搜索结果数据
		$http.get(base_url + 'app/get_app_search_results?n=' + query).success(function(data)
		{
			$scope.app_search_results = data.results;
			if ( data.results.length == 0 ) //如果没有搜索结果
			{
				$scope.app_search_results.splice(0,0,{"name":"没有相关结果"});
			}
			$scope.app_search_show_wait = false;
		});
	}
	
}


//app信息页控制器
function user_app_info_controller($scope, $http)
{
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

//关键词管理页面控制器
 function user_app_keyword_controller($scope, $http)
 {
	  //bootstrap tips
	  $("[data-toggle='popover']").popover({trigger:'click| hover',placement:'top',html:true});
	  
	  //加载提示
	  $scope.user_keywords_show = true;
	  $scope.recommend_keywords_show = false;
	  $scope.compete_keywords_show = false;
	  
	  
	  //关键词管理tab菜单管理
	  $scope.keyword_manage_class = true;
	  
	  //关键词推荐tab菜单管理
	  $scope.recommend_class = false;
	  $scope.compete_class = true;
	  $scope.expand_class = false;
	  
	  
	  //关键词管理tab点击
	  $scope.keyword_manage_click = function()
	  {
		  $scope.keyword_manage_class = true;
	  }
	  
	  
	  
	  //获取用户填写的keywords列表，初始化
	  url = base_url + 'user_app_keyword/get_user_app_keywords?app_id=' + app_id + '&email=' + email;
	  $http.get(url).success(function(data)
      {
          $scope.user_keywords = data;  
		  if (0 == $scope.user_keywords.length )
           {
                $scope.user_keywords.splice(0,0,{"word":"暂无关键词,请添加关键词"});
           }   
		  $scope.user_keywords_show = false;      
      });
	  
	  
	  //获取竞品,初始化
	  url = base_url + 'user_app/get_user_app_competes?app_id=' + app_id + '&email=' + email;
	  $http.get(url).success(function(data)
      {
          $scope.compete_apps = data;         
      });

	  
	  //删除关键词数据
	  $scope.remove = function(index)
	  {
		  word =  $scope.user_keywords[index]["word"];
		  //界面上删除
		  $scope.user_keywords.splice(index,1);
		  //真实删除数据库
		  url = base_url + 'user_app_keyword/del_user_app_keyword?n='+word + '&app_id=' + app_id + "&email=" + email;
		  $http.get(url).success(function(data)
      	  { });
	  }
	  
	  //添加关键词数据
	  $scope.append = function() 
	  {
		  //添加数据后，new_word可能是逗号隔开的多个词
		  //暂时只支持一个词
		  word = $scope.new_word;
		  if (word.length<2)
		  {
			  alert("关键词长度不能为1");
			  return -1;
		  }
		  /*
		  words = keywords.replace(/,|，/g,"|");
		  word_list = words.split("|");//字符串分割
		  //在界面上添加
		  for (index in word_list)
		  {
			  $scope.user_keywords.splice(0,0,{"word":word_list[index],"rank":"后台下载数据，最迟12小时内完成"});//插入第一个
		  }
		  */
		  //在界面上添加
		   $scope.user_keywords.splice(0,0,{"word":word,"rank":"后台下载数据，最迟12小时内完成"});//插入第一个
		  
		  //更新数据库，后台可处理多个字符串的情况
		  url = base_url + 'user_app_keyword/add_user_app_keyword?n=' + word + '&app_id=' + app_id + "&email=" + email;
	 	  $http.get(url).success(function(data)
      	  {});
	  }
	  

	  
	  //系统推荐关键词 tab点击
	  $scope.recommend_click = function()
	  {
		  $scope.recommend_class = true;
	  	  $scope.compete_class = false;
		  $scope.expand_class = false;
		  //获得推荐的keywords列表
	  	 $scope.recommend_keywords_show = true;    
		 url =  base_url + 'user_app_keyword/get_user_app_recommend_keywords?app_id=' + app_id +  "&email=" + email;
		 $scope.recommend_keywords = {};
		 $http.get(url).success(function(data)
		 {
			 $scope.recommend_keywords = data;   
			 $scope.recommend_keywords_show = false;      
		 });
	  }
	  
	  
	  
	  //竞品app tab点击
	  $scope.compete_click = function()
	  {
		  $scope.recommend_class = false;
	  	  $scope.compete_class = true;	
		  $scope.expand_class = false;	  
	  }
	  
	  //词扩展tab点击
	  $scope.expand_click = function()
	  {
		  $scope.recommend_class = false;
	  	  $scope.compete_class = false;	
		  $scope.expand_class = true;	  
	  }
	  
	  
	  //获得一个app的关键词列表
	  $scope.select_compete_app = function()
	  {
		  //清空table
		  $scope.compete_keywords_show = true;
		  $scope.app_keywords = {};
	      compete_app_id = $scope.compete_app_id;//从model获取选择的app_id
		  url = base_url + 'user_app_keyword/get_app_possible_keywords?app_id=' + compete_app_id + "&email=" + email;
		  $http.get(url).success(function(data)
      	  {
          	$scope.app_keywords = data;   
			$scope.compete_keywords_show = false;
             if (0 == $scope.app_keywords.length )
             {
                $scope.app_keywords.splice(0,0,{"query":"暂无关键词"});
             }      
      	  });
      }
	  
	//获得扩展词
	$scope.get_expand_word = function()
	{
		$scope.expand_words = {};
		name = $scope.query_word;
		$http.get(base_url + 'user_app_keyword/get_app_keyword_expand_keywords?n='+ name).success(function(data)
		{
			 $scope.expand_words = data;
			 if (0 ==  $scope.expand_words)
			 {
				 $scope.expand_words.splice(0,0,{"query":"暂无关键词"});
			}
		});
	}
}
