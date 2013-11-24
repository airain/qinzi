<?php
return array (
		//首页
		'IndexController'=>array(
				'index'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>1,'actname'=>'首页','type'=>0)
				)
		),
		//个人首页
		'MyHomeController'=>array(
				'index'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>215,'actname'=>'个人首页','type'=>1)
				)
		),
		'HomeController'=>array(
				'logout'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>55,'actname'=>'退出登陆','type'=>6)
				),
				'updates'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>41,'actname'=>'好友动态','type'=>10)
				),//我的/他的
				'timeline'=>array(
						'reg'		=>'/\/uid\/timeline/i',
						'exclude'	=>'',
						'val'		=>array('id'=>42,'actname'=>'我的车志','type'=>7),
						'hisval'	=>array('id'=>52,'actname'=>'他的车志','type'=>8),
				),
				'atme'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>43,'actname'=>'关注我的','type'=>7)
				),
				'detail'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>56,'actname'=>'他的作业详细','type'=>8)
				),
				'StoryDetail'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>56,'actname'=>'他的作业详细','type'=>8)
				),
				//他的达人会
				'clublist'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>214,'actname'=>'他的达人会','type'=>8)
				)
		),
		//我的车库
		'MyCarController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>22,'actname'=>'我的车库','type'=>7)
				)
		),
		'MyFeedsController'=>array(
				'mycomments'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>44,'actname'=>'我评论的','type'=>7)
				),
				'commentme'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>45,'actname'=>'评论我的','type'=>7)
				)
		),
		'MyFollowController'=>array(
				'myatts'=>array(
						'reg'		=>'/\/uid\/attentions/i',
						'exclude'	=>'',
						'val'		=>array('id'=>46,'actname'=>'我的关注','type'=>7),
						'hisval'	=>array('id'=>53,'actname'=>'他的关注','type'=>8)
				),
				'myfans'=>array(
						'reg'		=>'/\/uid\/fans/i',
						'exclude'	=>'',
						'val'		=>array('id'=>47,'actname'=>'我的车友','type'=>7),
						'hisval'	=>array('id'=>54,'actname'=>'他的车友','type'=>8)
				),
				'att_friend'=>array(//同上是一个页面,仅仅url不同.估计老数据
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>46,'actname'=>'我的关注','type'=>7)
				)
		),
		'MyFavoriteController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>48,'actname'=>'我的收藏','type'=>7)
				),
				'fav_activities'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>147,'actname'=>'收藏活动','type'=>7)
				)
		),
		'MessageController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>49,'actname'=>'私信','type'=>7)
				)
		),
        'NotificationController'=>array(
                    'index'=>array(
                            'reg'		=>'',
                            'exclude'	=>'',
                            'val'		=>array('id'=>51,'actname'=>'提醒','type'=>7)
                    )
            ),
		//达人会
		'ClubsController'=>array(
				'topics'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>103,'actname'=>'话题列表页','type'=>2)
				),
				'notes'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>104,'actname'=>'达人会内车志列表页','type'=>2)
				),
				'events'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>105,'actname'=>'达人会内活动列表页','type'=>2)
				),
				'members'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>106,'actname'=>'达人会内会员列表页','type'=>2)
				),
				'garage'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>107,'actname'=>'达人会内会员的车列表页','type'=>2)
				),
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>108,'actname'=>'我的达人会','type'=>2)
				),
				'mine'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>109,'actname'=>'我的达人会列表页','type'=>2)
				),
				'hots'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>110,'actname'=>'	热点广场页','type'=>2)
				),
				'all'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>111,'actname'=>'全部达人会页','type'=>2)
				),
				'search'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>112,'actname'=>'达人会搜索结果页','type'=>2)
				),
				'mytopics'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>113,'actname'=>'我发起的话题页','type'=>2)
				),
				'newclub'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>114,'actname'=>'申请创建达人会页','type'=>2)
				),
				'top'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>115,'actname'=>'达人会活跃度排行榜页','type'=>2)
				),
				'home'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>125,'actname'=>'达人会首页','type'=>2)
				)
		),
		//达人会管理
		'ClubsManageController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>173,'actname'=>'达人会管理--基本设置','type'=>2)
				),
				'avatar'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>116,'actname'=>'达人会管理--修改图标','type'=>2)
				),
				'settings'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>117,'actname'=>'达人会名称设置 - 汽车达人','type'=>2)
				),
				'approval'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>118,'actname'=>'达人会管理--申请审批','type'=>2)
				),
				'badges'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>119,'actname'=>'达人会管理--徽章管理','type'=>2)
				),
				'members'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>120,'actname'=>'达人会管理--会员管理','type'=>2)
				),
				'blacklist'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>121,'actname'=>'达人会管理--黑名单管理','type'=>2)
				),
				'managelog'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>122,'actname'=>'达人会管理--管理记录','type'=>2)
				)
		),
		//达人会管理
		'IssueController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>217,'actname'=>'达人会管理','type'=>2)
				)
		),
		//活动详情
		'ActivitiesController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>3,'actname'=>'活动广场','type'=>3)
				),
				'selfdrive'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>4,'actname'=>'活动广场--自驾','type'=>3)
				),
				'testdrive'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>5,'actname'=>'活动广场--试驾','type'=>3)
				),
				'groupbuy'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>6,'actname'=>'活动广场--团购','type'=>3)
				),
				'other'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>7,'actname'=>'活动广场--其他','type'=>3)
				),
				'online'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>8,'actname'=>'活动广场--线上','type'=>3)
				),
				'all'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>9,'actname'=>'活动广场--全部活动','type'=>3)
				),
				'map'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>10,'actname'=>'活动广场--地图模式','type'=>3)
				),
				'myactivity'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>11,'actname'=>'活动广场--我的活动','type'=>3)
				),
				'create'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>12,'actname'=>'创建活动','type'=>3)
				),
				'modify'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>13,'actname'=>'修改活动','type'=>3)
				),
				'preview'=>array(
						'reg'		=>'/\/activities\/preview\/\d+\/delete/i',
						'exclude'	=>'',
						'val'		=>array('id'=>14,'actname'=>'删除活动','type'=>3),
						'hisval'	=>array('id'=>220,'actname'=>'活动预览','type'=>3)
				),
				'comments'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>16,'actname'=>'活动讨论','type'=>3)
				),
				'images'=>array(
						'reg'		=>'/\/activities\/\d+\/images\/[0-9a-z]+/i',
						'exclude'	=>'',
						'val'		=>array('id'=>18,'actname'=>'活动图片详情','type'=>3),
						'hisval'	=>array('id'=>17,'actname'=>'活动图片','type'=>3)
				),
				'apply'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>19,'actname'=>'活动报名审批','type'=>3)
				),
				'memberlist'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>20,'actname'=>'活动成员','type'=>3)
				),
				'members'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>21,'actname'=>'活动成员管理','type'=>3)
				),
				'meeting'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>58,'actname'=>'活动广场-聚会','type'=>3)
				),
				'detail'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>15,'actname'=>'活动详情','type'=>3)
				),
				'myfav'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>218,'actname'=>'我的收藏-活动','type'=>7)
				),
				'query'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>219,'actname'=>'活动广场--搜索','type'=>3)
				),
		),
		//品牌
		'BrandController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>23,'actname'=>'找车大厅','type'=>4)
				),
				'brand'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>25,'actname'=>'品牌','type'=>4)
				)
		),
		//车系
		'SeriseController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>24,'actname'=>'车系','type'=>4)
				),
				'professiorEvaluationDetail'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>28,'actname'=>'专家评价','type'=>4)
				)
		),
			
		//车型
		'CarsController'=>array(
				'evaluate'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>26,'actname'=>'车主评价','type'=>4)
				),
				'owner'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>26,'actname'=>'车主评价','type'=>4)
				),
				'modelCar'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>27,'actname'=>'车型','type'=>4)
				),
				'options'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>29,'actname'=>'配置详情','type'=>4)
				),
				
		),
		//注册,
		'GuidedRegisterController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>222,'actname'=>'注册步骤','type'=>5)
				 )
		),
		'RegisterController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>213,'actname'=>'注册首页','type'=>5)
				),
				'sns'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>212,'actname'=>'微博注册','type'=>5)
				),
				'forgot'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>211,'actname'=>'忘记密码','type'=>5)
				),
				'activate'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>59,'actname'=>'激活邮箱','type'=>5)
				)
				,
				'invite'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>300,'actname'=>'邀请注册','type'=>5)
				)
		),
		//登录
		'SignupController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>210,'actname'=>'登录','type'=>6)
				)
		),
		//个人设置
		'SettingsController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>33,'actname'=>'基本信息设置','type'=>9)
				),
				'shares'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>34,'actname'=>'账号关联','type'=>9)
				),
				'password'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>35,'actname'=>'修改密码','type'=>9)
				),
				'setpassword'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>35,'actname'=>'修改密码','type'=>9)
				),
				'auth'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>36,'actname'=>'专家达人认证','type'=>9)
				),
				'photo'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>37,'actname'=>'修改头像','type'=>9)
				),
				'tags'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>38,'actname'=>'个人标签','type'=>9)
				),
				'watermark'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>39,'actname'=>'设置水印','type'=>9)
				),
				'moving'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>57,'actname'=>'论坛搬家','type'=>9)
				)
		),
		//手机客户端下载
		'MobileController'=>array(
				'download'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>200,'actname'=>'手机客户端下载','type'=>11)
				)
		),
		//搜索
		'SearchController'=>array(
				'search'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>201,'actname'=>'搜索结果','type'=>11)
				)
		),
		//关于我们
		'AboutController'=>array(
				'recruitment'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>202,'actname'=>'招聘','type'=>11)
				),
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>203,'actname'=>'关于我们','type'=>11)
				),
				'service'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>204,'actname'=>'服务条款','type'=>11)
				)
				,
				'dealers'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>204,'actname'=>'关于经销商','type'=>11)
				)
		),
		//邀请好友
		'InvitesController'=>array(
				'index'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>205,'actname'=>'邀请好友首页','type'=>11)
				),
				'invites'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>206,'actname'=>'email邀请','type'=>11)
				),
				'email'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>206,'actname'=>'email邀请','type'=>11)
				),
				'qq'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>207,'actname'=>'qq邀请','type'=>11)
				),
				'msn'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>208,'actname'=>'msn邀请','type'=>11)
				),
				'msnlist'=>array(
						'reg'		=>'',
						'exclude'	=>'',
						'val'		=>array('id'=>209,'actname'=>'邀请msn好友','type'=>11)
				)
		),		//手机端
		'ApiController'=>array(
				'recomuser'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>64,'actname'=>'推荐关注','type'=>15)
				),
				'myfollows'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>67,'actname'=>'我的车友','type'=>16)
				),
				'myatts'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>69,'actname'=>'我的关注','type'=>17)
				),
				'myinfo'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>70,'actname'=>'个人信息','type'=>18)
				),
				'atwho'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>74,'actname'=>'@谁','type'=>22)
				),
				'otherinfo'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>71,'actname'=>'获取他人信息','type'=>19)
				),
				'addatt'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>65,'actname'=>'添加关注','type'=>16)
				),
				'canceatt'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>66,'actname'=>'取消关注','type'=>16)
				),
				'moduserinfo'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>75,'actname'=>'修改用户资料','type'=>23)
				),
				'getstory'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>78,'actname'=>'单个作业','type'=>26)
				),
				'getstorybase'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>79,'actname'=>'获取单个作业基本信息','type'=>26)
				),
				'getmystories'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>77,'actname'=>'我的作业','type'=>25)
				),
				'getstorypics'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>80,'actname'=>'获取作业图片old','type'=>26)
				),
				'getstorypicall'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>81,'actname'=>'获取作业所有图片','type'=>26)
				),
				'savestory'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>86,'actname'=>'保存作业','type'=>27)
				),
				'modstory'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>82,'actname'=>'修改作业','type'=>26)
				),
				'delstory'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>83,'actname'=>'删除作业','type'=>26)
				),
				'getcmtlist'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>87,'actname'=>'作业评论列','type'=>28)
				),
				'savecmt'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>88,'actname'=>'保存作业评论','type'=>28)
				),
				'delcmt'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>89,'actname'=>'删除评论','type'=>28)
				),
				'addfavor'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>84,'actname'=>'收藏','type'=>29)
				),
				'delfavor'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>85,'actname'=>'取消收藏','type'=>29)
				),
				'getfavor'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>90,'actname'=>'获取收藏列表','type'=>29)
				),
				'sendmsg'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>96,'actname'=>'发送私信','type'=>32)
				),
				'delmsg'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>97,'actname'=>'删除私信','type'=>32)
				),
				'delmsgone'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>98,'actname'=>'删除私信单条内容','type'=>32)
				),
				'getmsg'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>99,'actname'=>'获取会话','type'=>32)
				),
				'addcar'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>91,'actname'=>'添加车','type'=>30)
				),
				'delcar'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>92,'actname'=>'删除车','type'=>30)
				),
				'getcarport'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>93,'actname'=>'我的车库','type'=>30)
				),
				'getpseries'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>94,'actname'=>'获取父车系','type'=>31)
				),
				'getmodels'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>95,'actname'=>'获取车型列','type'=>31)
				),
				'suggest'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>101,'actname'=>'退出','type'=>33)
				),
				'logout'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>100,'actname'=>'保存意见','type'=>33)
				),
				'savetopic'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>200,'actname'=>'发布话题','type'=>34)
				),
				'deltopic'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>201,'actname'=>'删除话题','type'=>34)
				),
				'getclubinfo'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>201,'actname'=>'达人会信息','type'=>34)
				),
				'gettopics'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>203,'actname'=>'达人会首页','type'=>34)
				),
				'gettopic'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>204,'actname'=>'话题详情页','type'=>34)
				),
				'gettopiccmt'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>205,'actname'=>'话题评论','type'=>34)
				),
				'clubs'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>206,'actname'=>'达人会广场','type'=>34)
				),
				'myclubs'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>207,'actname'=>'我的达人会','type'=>34)
				),
				'approval'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>208,'actname'=>'达人会审核成员','type'=>34)
				),
				'members'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>209,'actname'=>'达人会成员','type'=>34)
				),
				'delmember'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>210,'actname'=>'取消，退出达人会','type'=>34)
				),
				'addmember'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>211,'actname'=>'申请加入达人会','type'=>34)
				),
				'managemem'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>212,'actname'=>'达人会成员管理','type'=>34)
				),
				'modclub'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>213,'actname'=>'更新达人会logo图','type'=>34)
				),
				'upclubbg'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>214,'actname'=>'更新达人会背景','type'=>34)
				),
				'searchclub'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>215,'actname'=>'搜索达人会','type'=>34)
				),
				'clubsign'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>216,'actname'=>'达人会签到','type'=>34)
				),
				'getsharestory'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>300,'actname'=>'获取分享作业','type'=>35)
				),
				'sendsharestory'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>301,'actname'=>'发送分享作业','type'=>35)
				),
				'getshares'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>302,'actname'=>'获取绑定帐号信息','type'=>35)
				),
				'bind'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>303,'actname'=>'绑定第三方帐号','type'=>35)
				),
				'unbind'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>304,'actname'=>'取消绑定第三方帐号','type'=>35)
				),
				'setshare'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>305,'actname'=>'分享设置','type'=>35)
				),

				'feeds'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>306,'actname'=>'好友动态','type'=>36)
				),
				'login'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>60,'actname'=>'登录','type'=>13)
				)
				,
				'snslogin'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>60,'actname'=>'微博登录','type'=>13)
				)
				,
				'getstories'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>76,'actname'=>'作业列','type'=>24)
				)
				,
				'register'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>62,'actname'=>'注册','type'=>14)
				),
				'cmtme'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>73,'actname'=>'注册','type'=>21)
				)
				,
				'atme'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>72,'actname'=>'@我的列表','type'=>20)
				)
				,
				'snsreg'=>array(
						'reg'=>'',
						'exclude'=>'',
						'val'=>array('id'=>63,'actname'=>'@我的列表','type'=>14)
				)
		)

);