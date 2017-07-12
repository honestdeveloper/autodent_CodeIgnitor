<?php
	$calendarItems = $calendar->getMonth();
	$appts = [];
	
	if(!empty($calendarItems)){
		reset($calendarItems);
		$appt_from = key($calendarItems);
		end($calendarItems);
		$appt_to = key($calendarItems);
		$appts = $this->customers_model->getSchedule($appt_from, $appt_to);
	}
?>

<div class="calendar month">
	
	<?foreach($calendar->getWeekDays() as $wd):?>
	<div class="col title"><?=$wd?></div>
	<?endforeach;?>
	
	
	<?$i=1;foreach($calendarItems as $date => $opt):?>
	<?if($i%7==1) echo '<div class="wr">';?>
	<div id="col-<?=$date?>" class="col cell <?=$opt->classes?>">
		<b><?=$opt->day?></b>
		<?if(!$opt->disabled && !empty($appts[$date])):?>
		<div class="points">
		<?foreach($appts[$date] as $appt):?><i class="point <?=$appt->date_color?>" data-id="<?=$appt->id?>"></i><?endforeach;?>
		</div>
		
		<div class="chevron"><i class="glyphicon glyphicon-chevron-up"></i></div> 
		<?endif;?>
	</div>
	<?if($i%7==0) echo '</div>';?>
	<?$i++;endforeach;?>
	
	

	<ul class="appts">
		<?foreach($calendarItems as $date => $opt): if(!$opt->disabled && !empty($appts[$date])): foreach($appts[$date] as $appt):?>
		<li data-col="col-<?=$date?>" data-id="<?=$appt->id?>">
			<a href="#" onclick="return false;">
			<div class="point <?=$appt->date_color?>"></div>
			<div class="name capit"><?=$appt->name?></div>
			<div class="time">(<?=$appt->date_name?>, <?=date('h:i A',strtotime($appt->task_datetime))?>)</div>
			</a>
			
			<div class="btns">
				<button class="btn btn-danger btn-sm margin-right-10 delete-appt" data-id="<?=$appt->id?>"><i class="ti-close"></i></button>
				<button class="btn btn-primary btn-sm pull-right edit-appt" data-id="<?=$appt->customer_id?>"><i class="ti-pencil"></i></button>
			</div>
			
		</li>
		<?endforeach; endif; endforeach;?>
	</ul>		

	
	
</div>


<script>
	$('.calendar .wr').eq($('.calendar .wr').length-1).addClass('last');
	
	$('.calendar .col.cell').click(function(){
		if($(this).hasClass('disabled')) return false;
		//--
		$(".appts").slideUp(0);
		$('.calendar .col.cell').removeClass('active');
		$(this).addClass('active');
		//--
		var id = $(this).attr('id');
		$(".appts").detach().insertAfter($(this).parent());
		$('.appts > li').hide(0,function(){
			$('.appts > li').each(function(){
				if($(this).attr('data-col') == id) $(this).show();
			});
		});
		$(".appts").slideDown(300);
		return false;
	});
	
	$('.calendar .col.cell .chevron').click(function(){
		$(".appts").slideUp(0);
		$(this).parent(this).removeClass('active');
		return false;
	});
</script>

