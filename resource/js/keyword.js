// JavaScript Document

//定义全局变量
//var base_url = "http://git.appbk.com/";
//var app_id = "728200220";
//关键词控制器
 function controller($scope, $http)
 {
      //加载提示
      $scope.user_keywords_show = true;
      $scope.recommend_keywords_show = false;
      $scope.compete_keywords_show = false;
      
      //tab菜单管理
      $scope.recommend_class = "";
      $scope.compete_class = "active";
      
      //获取用户填写的keywords列表，初始化
      $http.get(base_url + 'user_app_process/get_user_keywords?app_id=' + app_id).success(function(data)
      {
          $scope.user_keywords = data;  
          if (0 == $scope.user_keywords.length )
           {
                $scope.user_keywords.splice(0,0,{"word":"暂无关键词,请添加关键词"});
           }   
          $scope.user_keywords_show = false;      
      });
      
      
      //获取竞品,初始化
      $http.get(base_url + 'user_app_process/get_relate_apps?app_id=' + app_id).success(function(data)
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
          $http.get(base_url + 'user_app_process/del_user_keyword?keyword='+word + '&app_id=' + app_id).success(function(data)
          { });
      }
      
      //添加关键词数据
      $scope.append = function() 
      {
          //添加数据后，new_word可能是逗号隔开的多个词
          keywords = $scope.new_word;
          words = keywords.replace(/,|，/g,"|");
          word_list = words.split("|");//字符串分割
          //在界面上添加
          for (index in word_list)
          {
              $scope.user_keywords.splice(0,0,{"word":word_list[index],"rank":"后台更新，稍后刷新页面"});//插入第一个
          }
          //更新数据库，后台可处理多个字符串的情况
          $http.get(base_url + 'user_app_process/append_user_keyword?keyword=' + keywords + '&app_id=' + app_id).success(function(data)
          {});
      }
      

      
      //系统推荐关键词 tab点击
      $scope.recommend_click = function()
      {
          $scope.recommend_class = "active";
          $scope.compete_class = "";
          //获得推荐的keywords列表
         $scope.recommend_keywords_show = true;     
        $http.get(base_url + 'user_app_process/get_recommend_keywords?app_id=' + app_id).success(function(data)
        {
          $scope.recommend_keywords = data;   
          $scope.recommend_keywords_show = false;      
        });
      }
      
      
      //竞品app tab点击
      $scope.compete_click = function()
      {
          $scope.recommend_class = "";
          $scope.compete_class = "active";        
      }
      
      //获得一个app的关键词列表
      $scope.select_compete_app = function()
      {
          //清空table
          $scope.compete_keywords_show = true;
          $scope.app_keywords = {};
          app_id = $scope.compete_app_id;//从model获取选择的app_id
          $http.get(base_url + 'user_app_process/get_app_keywords?app_id=' + app_id).success(function(data)
          {
            $scope.app_keywords = data;   
            $scope.compete_keywords_show = false;
             if (0 == $scope.app_keywords.length )
             {
                $scope.app_keywords.splice(0,0,{"query":"暂无关键词"});
             }      
          });
      }
}
