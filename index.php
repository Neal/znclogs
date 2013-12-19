<?php require_once __dir__ . '/model.php'; ?>
<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='utf-8'>
		<title>ZNC Logs</title>
		<link href='//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css' rel='stylesheet'>
		<style>
			body { padding-top: 20px; }
			.table-center th { vertical-align: middle; text-align: center; }
			.table-center td { vertical-align: middle; text-align: center; }
		</style>
	</head>

	<body>
		<div class='container'>

			<?php /* Complicated. I know. */ ?>
			<ul class='breadcrumb'>
				<?php if((empty($get_network)) && (empty($get_channel)) && (empty($get_date))): ?>
				<li class='active'><?=USER?></li>
				<?php else: ?>
				<li><a href='<?=BASE_URL_PATH?>'><?=USER?></a></li>
				<?php 	if(empty($get_channel)): ?>
				<li class='active'><?=$get_network?></li>
				<?php 	else: ?>
				<li><a href='<?=BASE_URL_PATH.$get_network?>'><?=$get_network?></a></li>
				<?php 		if(empty($get_date)): ?>
				<li class='active'><?=$get_channel?></li>
				<?php 		else: ?>
				<li><a href='<?=BASE_URL_PATH.$get_network.'/'.$get_channel?>'><?=$get_channel?></a></li>
				<li class='active'><?=date('D, F d Y', mktime(0, 0, 0, substr($get_date, 4, 2), substr($get_date, -2), substr($get_date, 0, 4)))?></li>
				<?php 		endif ?>
				<?php 	endif ?>
				<?php endif ?>
			</ul>

			<?php if ((!empty($get_network)) && (!empty($get_channel)) && (!empty($get_date))): ?>

				<?php if (array_key_exists('#'.$get_channel, $networks[$get_network])) $get_channel = '#'.$get_channel; ?>
				<?php if (array_key_exists('##'.$get_channel, $networks[$get_network])) $get_channel = '##'.$get_channel; ?>
				<?php $log = file_get_contents($networks[$get_network][$get_channel][$get_date]['location']); ?>
				<?php $log = htmlentities($log, ENT_IGNORE); ?>
				<?php $log = preg_replace('~(?:www|http://)\S+~', '<a href="$0">$0</a>', $log); ?>
			<div>
				<pre style='font-family: Consolas,monospace;'>
<?=$log?>
				</pre>
			</div>

			<?php elseif (empty($get_network)): ?>

			<div>
				<ul>
					<?php foreach ($networks as $network => $channels): ?>
					<li><a href='/znclogs/<?=str_replace($illegal_characters, null, $network)?>'><?=$network?></a></li>
					<?php endforeach ?>
				</ul>
			</div>

			<?php elseif (empty($get_channel)): ?>

			<div>
				<ul>
					<?php foreach ($networks[$get_network] as $channel => $dates): ?>
					<li><a href='/znclogs/<?=$get_network?>/<?=str_replace($illegal_characters, null, $channel)?>'><?=$channel?></a></li>
					<?php endforeach ?>
				</ul>
			</div>

			<?php elseif (empty($get_date)): ?>

			<div>
				<ul>
					<?php foreach ($networks[$get_network] as $channel => $dates): ?>
					<?php   if ($get_channel == $channel || '#'.$get_channel == $channel || '##'.$get_channel == $channel): ?>
					<?php     foreach ($dates as $date => $log_info): ?>
					<li><a href='/znclogs/<?=$get_network?>/<?=$get_channel?>/<?=str_replace($illegal_characters, null, $date)?>'><?=date('D, F d Y', mktime(0, 0, 0, substr($date, 4, 2), substr($date, -2), substr($date, 0, 4)))?></a></li>
					<?php     endforeach ?>
					<?php   endif ?>
					<?php endforeach ?>
				</ul>
			</div>

			<?php endif ?>

			<hr>
			<footer class='footer'>
				<p class='pull-right'><a href='#'>Back to top</a></p>
			</footer>

		</div> <!-- /container -->
	</body>
</html>
