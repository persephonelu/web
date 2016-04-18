<!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">  


    <h3 class="page-header">app关键词管理</h3>
    
      <div class="panel panel-default">
        <div class="panel-heading"> 我的关键词列表 </div>
        <div class="panel-body">    
			     
            <table  class="table table-striped table-bordered">
                <tr>
                  <th width="9%">序号</th>
                  <th>搜索词</th>
                  <th title="搜索热度反映每天搜索的次数多少">搜索热度 <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                  <th>搜索结果数</th>
                  <th>第1名APP</th>
                  <th>删除</th>
                </tr>
                
                 <tr ng-repeat="word in user_keywords">
                  <td>{{$index+1}}</td>
                  <td>{{word.word}}</td>
                  <td>{{word.rank}}</td>
                  <td>{{word.num}}</td>
                  <td><span class="c2">{{word.name}}</span></td>
                  <td><a href="#" ng-click="remove($index)">删除</a></td> 
                 </tr>

           </table>
           <img ng-show="user_keywords_show" src="http://cdn.appbk.com/images/wait1.gif">

		 <br/>
		 <div class="row" id="form_area">
               <div class="col-lg-8">
                <form role="form" class="form-inline" name="append_user_keyword" ng-submit="append()">
                    <div class="input-group col-sm-10">
                    <input type="text" class="form-control" name="q" placeholder="输入一个或多个关键词，多个词用逗号隔开" value="" ng-model="new_word" ng-minlength='2'>
                    </div>
                    <button  ng-disabled="!append_user_keyword.$valid" class="btn btn-primary">添加关键词</button>
              </form>
              </div>

         </div>
         <!-- /#from_area -->

        </div>
      </div>
      <!-- / panel-->
      

		<div class="panel panel-default">
            <div class="panel-heading">关键词推荐</div>
            <div class="panel-body">
			 <ul class="nav nav-tabs">
               <li role="presentation" class="{{compete_class}}" id="compete"><a href="#" ng-click="compete_click()" name="compete_nav" id="compete_nav">竞品关键词</a></li>
              <li role="presentation" class="{{recommend_class}}" id="recommend"><a href="#" ng-click="recommend_click()">系统推荐关键词</a></li>
			</ul>
            
       		<div class="tab-content"> 
      
                <!-- 竞品关键词 -->
                <div class="tab-pane {{compete_class}}" id="compete_keywords">
                <br/>
                <div class="row" id="form_area">
               	<div class="col-lg-8">
                	<form role="form" class="form-inline">
                        <div class="form-group">
                        <label>选择竞品app: </label>
                        <select class="form-control" ng-model="compete_app_id" ng-change="select_compete_app()">
                            <option ng-repeat="app in compete_apps" value="{{app.app_id}}">{{app.name|limitTo:10}}</option>
                         </select>
                        </div>
              		</form>
              	</div>
         		</div>
         <!-- /#from_area -->

				<br/>
                
                <table  class="table table-striped table-bordered">
                    <tr>
                      <th width="9%">序号</th>
                      <th>搜索词</th>
                      <th title="搜索热度反映每天搜索的次数多少">搜索热度 <span class="glyphicon glyphicon-question-sign text-info"></span></th>
                      <th>搜索结果数</th>
                      <th>第1名APP</th>
                    </tr>
                    
                     <tr ng-repeat="word in app_keywords">
                      <td>{{$index+1}}</td>
                      <td>{{word.query}}</td>
                      <td>{{word.rank}}</td>
                      <td>{{word.num}}</td>
                      <td><span class="c2">{{word.name}}</span></td>
                     </tr>
           		</table>	
                <!-- /#compete_keywords 竞品关键词 -->
                <img ng-show="compete_keywords_show" src="http://cdn.appbk.com/images/wait1.gif">                

                </div> 
                <!-- /#compete_keywords -->
             <div class="tab-pane {{recommend_class}}" id="recommend_keywords">
                <br/>
                <table  class="table table-striped table-bordered">
                    <tr>
                      <th width="9%">序号</th>
                      <th>搜索词</th>
                      <th title="搜索热度反映每天搜索的次数多少">搜索热度 <span class="glyphicon glyphicon-question-si
gn text-info"></span></th>
                      <th>搜索结果数</th>
                      <th>第1名APP</th>
                    </tr>

                     <tr ng-repeat="word in recommend_keywords">
                      <td>{{$index+1}}</td>
                      <td>{{word.word}}</td>
                      <td>{{word.rank}}</td>
                      <td>{{word.num}}</td>
                      <td><span class="c2">{{word.name}}</span></td>
                     </tr>
                </table> 
            
                <img ng-show="recommend_keywords_show" src="http://cdn.appbk.com/images/wait1.gif">

                </div>
                <!-- /#recommend_keywords -->

            </div> 
            <!-- /.table_content --> 
       </div>
        <!-- / panel-->



                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->


