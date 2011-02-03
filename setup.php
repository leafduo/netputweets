<?php
error_reporting(E_ALL ^ E_WARNING);
$base_url = 'http://'.$_SERVER['HTTP_HOST'];
if ($directory = trim(dirname($_SERVER['SCRIPT_NAME']), '/\,')){
	$base_url .= '/'.$directory;
}
define('BASE_URL', $base_url.'/');
define('ABSPATH', dirname(__FILE__).'/');
$configFile = file(ABSPATH . 'config-sample.php');
$notice = '';

if (!function_exists("curl_init")) {
	$notice = '<strong>提示：</strong>服务器不支持 cURL 函数。奶瓶腿将无法使用。';
} elseif (file_exists(ABSPATH.'config.php')) {
	$notice = '<strong>提示：</strong> config.php 文件已存在。如果您想更改 config.php 内已有的设定，请先删除它，本向导会重新创建 config.php 。<a href="setup.php">重试</a>。';
} elseif (!file_exists(ABSPATH.'config-sample.php')) {
	$notice = '<strong>提示：</strong>未能检测到 config-sample.php 文件。请确认该目录存在此文件或重新上传。';
} elseif (!is_writable(ABSPATH)) {
	$notice = '<strong>提示：</strong>目录不可写。请更改目录属性或者手动创建 config.php 。';
}

if (isset($_GET['step'])) {
	$step = $_GET['step'];
} else {
	$step = 0;
}

function rewritable() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, BASE_URL.'settings');
	curl_exec($ch);
	$response_info = curl_getinfo($ch);
	
	if ($response_info["http_code"] == "404") return false;
	return true;
}

function display_header($n) {
	header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html><meta charset=utf-8 /><title>安装向导 - 奶瓶腿!</title><link rel="stylesheet" href="https://t.orzdream.com/images/setup.css" type="text/css" /><h1 id="logo"><img alt="奶瓶腿" src="https://t.orzdream.com/images/setup.jpg" /></h1><?php
	if ($n !== ''){
		echo '<div id="error">'.$n.'</div>';
	}
}
switch($step) {
	case 0:
		display_header($notice);
?><p><strong>欢迎使用「奶瓶腿」！</strong><p /><a href="https://t.orzdream.com/">奶瓶腿</a>是一个安全的、个性的中文 Twitter 手机客户端，在 <a href="http://dabr.co.uk">Dabr</a> (By <a href="https://twitter.com/davidcarrington">@davidcarrington</a>) 的基础上进行修改，同时也感谢 <a href="https://twitter.com/iChada">@iChada</a> <a href="https://twitter.com/17th">@17th</a> <a href="https://twitter.com/luosheng">@luosheng</a> <a href="https://twitter.com/lonelyswan">@LonelySwan</a> 的贡献和协助。如果您有任何建议、意见或疑问，请推 <a href="https://twitter.com/NetPuter">@NetPuter</a> 。<p />在正式使用之前，你需要填写一些信息，可能包括：</p><ol><li>Twitter OAuth Consumer Key & Secret <a href="https://twitter.com/apps/new" title="申请地址">#</a></li><li>Twitpic API Key <a href="http://dev.twitpic.com/apps/new" title="申请地址">#</a></li><li>Twitter API Proxy <a href="http://code.google.com/p/twip/" title="项目地址">#</a></li></ol><p><strong>如果无法进入下一步，别着急。此向导的目的在于创建「奶瓶腿」的配置文件，所以您还可以直接用文本编辑器打开 <code>config-sample.php</code> ，根据提示填写相应信息，然后保存并将它重命名为 <code>config.php</code> 。</strong></p><p>建议在安装前仔细阅读《<a href="http://orzdream.com/2009/10/netputweets-guide/" title="奶瓶腿简明架设教程">奶瓶腿简明架设教程</a>》，如果还要不明白的地方，请推 <a href="https://twitter.com/NetPuter">@NetPuter</a> 。如果已经准备好了 &hellip; &hellip;</p><?php
		if ($notice !== ''){
			echo '<p class="step"><a href="setup.php" class="button">还不能开始！</a></p>';
		}else{
			echo '<p class="step"><a href="setup.php?step=1" class="button">现在就开始吧！</a></p>';
		}
	break;
	case 1:
		display_header($notice);
?><form method="post" action="setup.php?step=2"><p>请在下面的表单中填入对应的信息。</p><table class="form-table"><tr><th scope="row"><label for="t_title">标题</label></th><td><input name="t_title" id="t_title" type="text" value="奶瓶腿!" size="35" /></td><td>如「<a href="https://t.orzdream.com/settings">设置 - 奶瓶腿!</a>」</td></tr><tr><th scope="row"><label for="t_urf">网址</label></th><td><input name="t_urf" id="t_urf" type="text" value="<?php echo BASE_URL;?>" size="35" /></td><td>一般不需要修改 注意要以 / 结尾</td></tr><tr><th scope="row"><label for="t_ck">Cookie 密匙</label></th><td><input name="t_ck" id="t_ck" type="text" size="35" value="NetPutweets" /></td><td>如果有在同一主机内安装多个奶瓶腿的需要请修改此项</td></tr><tr><th scope="row"><label for="t_tck">Twitter OAuth Consumer Key <a href="https://twitter.com/apps/new" title="申请地址">#</a></label></th><td><input name="t_tck" id="t_tck" type="text" size="35" value="awGBKfiSSqf1B2iKGsmJQ" /></td><td>一般需要修改</td></tr><tr><th scope="row"><label for="t_tcs">Twitter OAuth Consumer Secret <a href="https://twitter.com/apps/new" title="申请地址">#</a></label></th><td><input name="t_tcs" id="t_tcs" type="text" size="35" value="hym4qJF1F6nyjISzRUCFBU4OQSIr5mrk7074vId3K8" /></td><td>一般需要修改</td></tr><tr><th scope="row"><label for="t_tak">Twitpic API Key <a href="http://dev.twitpic.com/apps/new" title="申请地址">#</a></label></th><td><input name="t_tak" id="t_tak" type="text" value="95a105b0faadab9daa1a5739d4f80992" size="35" /></td><td>一般不需要修改</td></tr><tr><th scope="row"><label for="t_ta">Twitter API <a href="http://code.google.com/p/twip/" title="项目地址">#</a></label></th><td><input name="t_ta" id="t_ta" type="text" value="http://api.twitter.com/1/" size="35" /></td><td>如果您的主机在国内 请修改此项</td></tr><tr><th scope="row"><label for="t_tsa">Twitter Search API</label></th><td><input name="t_tsa" id="t_tsa" type="text" value="http://search.twitter.com/" size="35" /></td><td>如果您的主机在国内 请修改此项</td></tr><tr><th scope="row"><label for="t_rsl">还原短链接</label></th><td><select name="t_rsl"><option value="1">开启</option><option selected="selected" value="0">停用</option></select></td><td>如果您的主机在国内 请停用此项 开启此功能可能会影响一点点儿速度 并无大碍</td></tr><tr><th scope="row"><label for="t_ivt">仅受邀用户可登录</label></th><td><select name="t_ivt"><option value="1">开启</option><option selected="selected" value="0">停用</option></select></td><td>请根据您的需要选择</td></tr><tr><th scope="row"><label for="t_psw">设置邀请码</label></th><td><input name="t_psw" id="t_psw" type="text" value="twitter" size="20" /></td><td>用于「 <a href="invite.php">invite.php</a> 」</td></tr><tr><th scope="row"><label for="t_ssl">强制使用 SSL 加密连接</label></th><td><select name="t_ssl"><option value="1">开启</option><option selected="selected" value="0">停用</option></select></td><td>一般不需要修改 请根据您的空间情况选择</td></tr><tr><th scope="row"><label for="t_iphone">强制使用 SSL 加密连接</label></th><td><select name="t_iphone"><option value="1">开启</option><option selected="selected" value="0">停用</option></select></td><td>一般不需要修改 请根据您的空间情况选择</td></tr>/table><?php
		if ($notice !== ''){
			echo '<p class="step"><a href="setup.php" class="button">出错了！</a></p>';
		}else{
			echo '<p class="step"><input name="submit" type="submit" value="填好了！" class="button" /></p>';
		}
		echo '</form>';
		break;
	case 2:
		if (!isset($_POST['submit'])) {
			header('location: index.php');
		} else {
			$t_title= (empty($t_title)) ? trim($_POST['t_title']) : '奶瓶腿!';
			$t_urf	= (empty($t_urf)) ? trim($_POST['t_urf']) : BASE_URL;
			$t_ck	= (empty($t_ck)) ? trim($_POST['t_ck']) : 'NetPutweets';
			$t_tck	= (empty($t_tck)) ? trim($_POST['t_tck']) : 'awGBKfiSSqf1B2iKGsmJQ';
			$t_tcs	= (empty($t_tcs)) ? trim($_POST['t_tcs']) : 'hym4qJF1F6nyjISzRUCFBU4OQSIr5mrk7074vId3K8';
			$t_tak	= (empty($t_tak)) ? trim($_POST['t_tak']) : '95a105b0faadab9daa1a5739d4f80992';
			$t_ta	= (empty($t_ta)) ? trim($_POST['t_ta']) : 'http://api.twitter.com/1/';
			$t_tsa	= (empty($t_tsa)) ? trim($_POST['t_tsa']) : 'http://search.twitter.com/';
			$t_rsl	=  trim($_POST['t_rsl']);
			$t_ivt	=  trim($_POST['t_ivt']);
			$t_psw	= (empty($t_psw)) ? trim($_POST['t_psw']) : 'twitter';
			$t_ssl	=  trim($_POST['t_ssl']);
            $t_iphone = trim($POST['t_iphone']);

			if (rewritable()) {
				$t_url = $t_urf;
			} else {
				$t_url = $t_urf.'index.php?q=';
			}
			
			if ($notice == '') {
				$handle = fopen(ABSPATH . 'config.php', 'w');
				foreach ($configFile as $line_num => $line) {
					switch (substr($line,11,5)) {
						case "RYPTI":
							fwrite($handle, str_replace('putyourinfohere', $t_ck, $line));
							break;
						case "TH_KE":
							fwrite($handle, str_replace('putyourinfohere', $t_tck, $line));
							break;
						case "TH_SE":
							fwrite($handle, str_replace('putyourinfohere', $t_tcs, $line));
							break;
						case "TPIC_":
							fwrite($handle, str_replace('putyourinfohere', $t_tak, $line));
							break;
						case "E_URL":
							fwrite($handle, str_replace('putyourinfohere', $t_url, $line));
							break;
						case "E_URF":
							fwrite($handle, str_replace('putyourinfohere', $t_urf, $line));
							break;
						case "_TITL":
							fwrite($handle, str_replace('putyourinfohere', $t_title, $line));
							break;
						case "_URL'":
							fwrite($handle, str_replace('putyourinfohere', $t_ta, $line));
							break;
						case "_URLS":
							fwrite($handle, str_replace('putyourinfohere', $t_tsa, $line));
							break;
						case "G_URL":
							fwrite($handle, str_replace('0', $t_rsl, $line));
							break;
						case "ITE',":
							fwrite($handle, str_replace('0', $t_ivt, $line));
							break;
						case "ITE_C":
							fwrite($handle, str_replace('putyourinfohere', $t_psw, $line));
							break;
						case "CE_SS":
							fwrite($handle, str_replace('0', $t_ssl, $line));
							break;
						default:
							fwrite($handle, $line);
					}
				}
				fclose($handle);
				chmod(ABSPATH.'config.php', 0666);
                chmod(ABSPATH.'invite.php', 0666);

                if ($t_iphone == 1) {
                    //iPhone only
                    chmod(ABSPATH.'.htaccess', 0666);
                    $handle = fopen(ABSPATH . '.htaccess', 'a');
                    fwrite($handle, '\nOrder deny,allow\nDeny from all\nBrowserMatch iPhone ok\nBrowserMatch MobileSafari ok\nAllow from env=ok\n');
                    fclose($handle);
                    chmod(ABSPATH.'.htaccess', 0644);
                }
				display_header($notice);
?><p>恭喜！奶瓶腿已经安装成功。准备好了？开始 &hellip; &hellip;</p><p class="step"><a href="<?php echo BASE_URL.'" class="button">抱抱奶瓶腿！</a></p>';
			}
		}
	break;
}
?>
