#!/bin/sh
#更新修改到svn
#step 1,add 新文件
svn st |grep ?|awk "{print $2}"|xargs svn add

#step 2,commit 所有更新
svn commit -m "test machine commit"

#step 3，update 确认
svn update
