<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//test
$route['category'] = "Test";

//------------------------------upload-----------------------------------------

//upload images
$route['event/upload'] = 'Upload/uploadEventPhoto';

$route['news/upload'] = 'Upload/uploadNewsPhoto';

$route['user/upload'] = 'Upload/uploadUserPhoto';

//sned village notification
$route['event/notification'] = 'MultiplePush/pushNotification';

//send connection or event village notificatipn
$route['event/con/notification'] = 'MultiplePush/pushAllNotification';

//------------------------------user login-----------------------------------------

//user register
$route['user/register'] = 'UserOperation/register';

//user login
$route['user/login'] = 'UserOperation/login';

//user logout
$route['user/logout'] = 'UserOperation/logout';


//user facebook login
$route['user/fclogin'] = 'UserOperation/fclogin';

//get state
$route['user/state'] = 'UserOperation/getState';

//get district
$route['user/district'] = 'UserOperation/getDistrict';

//get taluka
$route['user/taluka'] = 'UserOperation/getTaluka';

//get village
$route['user/village'] = 'UserOperation/getVillage';

//edit profile
$route['user/update'] = 'UserOperation/editProfile';

//get notification
$route['user/notification/(\d+)'] = 'UserOperation/getNotification';

//request otp
$route['user/request/otp'] = 'UserOperation/requestOtp';

//verify otp
$route['user/verify/mobile'] = 'UserOperation/verifyMobile';

$route['user/account/info/(\d+)'] = 'UserOperation/getAccountInfo';

$route['user/account/submit'] = 'UserOperation/submitAccount';

$route['user/refund/history/(\d+)'] = 'UserOperation/refundHistory';


//---------------------------------My village----------------------------------

//get My Village Data
$route['myvillage/data/(\d+)'] = 'MyVillage/getMyVillageData';

//get villages to add in list
$route['connection/select'] = 'Connection/selectVillage';

//---------------------------------All village----------------------------------

//get My Village Data
$route['allvillage/data/(\d+)'] = 'AllVillage/getAllVillageData';

//---------------------------------Connection----------------------------------

//get connection list
$route['connection/myconnection(\d+)'] = 'Connection/MyConnection';

//get villages to add in list
$route['connection/villagelist'] = 'Connection/VillageSelectionList';

//add connection in list
$route['connection/add'] = 'ConnectionOperation/addConnection';

//remove connection from list
$route['connection/remove/(\d+)'] = 'ConnectionOperation/removeConnection';

//------------------------------Event-----------------------------------------

//create event
$route['event/create'] = 'EventOperation/createEvent';

//event photos
$route['event/photos/(\d+)'] = 'Event/getEventPhotos';

//add event village
$route['event/village/add'] = 'EventOperation/addEventVillage';

//add event myvillage
$route['event/myvillage/add'] = 'EventOperation/addDefaultUserVillage';

//remove village from list
$route['event/remove/village'] = 'EventOperation/removeVillage';

//send sms
$route['event/send/sms'] = 'EventOperation/sendSms';

//send sms
$route['event/load/matter'] = 'EventOperation/loadEventMatter';


//------------------------------News-----------------------------------------

//create news
$route['news/create'] = 'NewsOperation/createNews';

//event photos
$route['news/photos/(\d+)'] = 'News/getNewsPhotos';

//create event
$route['news/rssfeed/(\d+)'] = 'News/getRssFeed';

//news photos
$route['news/photos/(\d+)'] = 'News/getNewsPhotos';

//------------------------------Directory------------------------------------

//access village Directory
$route['directory/select/(\d+)'] = 'VillageDirectory/getVillageDirectory';

//create village Directory
$route['directory/create'] = 'VillageDirectory/addDirectoryContact';

//get my data
$route['directory/my_dir/(\d+)'] = 'VillageDirectory/getMyData';

//------------------------------User------------------------------------

//access user ads
$route['user/myads/(\d+)'] = 'User/getMyEvent';

//edit user ads
$route['user/myads/edit'] = 'MyEventOperation/editMyAd';

//edit user ads
$route['user/myadvillage/edit'] = 'MyEventOperation/editMyAdVillage';

//delete user ads
$route['user/myads/delete/(\d+)'] = 'MyEventOperation/deleteMyAd';

//pay for user ads
$route['user/myads/pay'] = 'MyEventOperation/payMyAd';

//create user account
$route['user/myacct/create'] = 'AccountOperation/createMyAccount';

//access user ads
$route['user/myaccount/(\d+)'] = 'User/getMyAccount';

//edit user account
$route['user/myacct/edit'] = 'AccountOperation/editMyAccount';

//delete user account
$route['user/myacct/delete/(\d+)'] = 'AccountOperation/deleteMyAccount';

//update user status
$route['user/notification/status']='UserOperation/updateNotificationStatus';


//------------------------------village boy login-----------------------------------------

//vb register
$route['vb/register'] = 'VbOperation/register';

//vb login
$route['vb/login'] = 'VbOperation/login';

//vb update
$route['vb/update'] = 'VbOperation/editProfile';

//---------------------------------My ads/news----------------------------------

//get my ad
$route['vb/ads/(\d+)'] = 'VillageBoy/getMyAd';

//get my news
$route['vb/news/(\d+)'] = 'VillageBoy/getMyNews';

//get my payment
$route['vb/payment/(\d+)'] = 'VillageBoy/getMyPayment';

//get my account summury
$route['vb/account/summury/(\d+)'] = 'VillageBoy/getAccountSummury';
