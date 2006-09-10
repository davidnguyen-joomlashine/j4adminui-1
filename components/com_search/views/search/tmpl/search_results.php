<table class="searchintro<?php echo $params->get( 'pageclass_sfx' ); ?>">
<tr>
	<td colspan="3" >
		<?php echo JText::_( 'Search Keyword' ) .' <b>'. stripslashes($searchword) .'</b>'; ?>
	</td>
</tr>
<tr>
	<td>
		<br />
		<?php eval ('echo "'. $result .'";'); ?>
		<a href="http://www.google.com/search?q=<?php echo $searchword; ?>" target="_blank">
			<?php echo $image; ?>
		</a>
	</td>
</tr>
</table>
<br />
<div align="center">
	<div style="float: right;">
		<label for="limit">
			<?php echo JText::_( 'Display Num' ); ?>
		</label>
		<?php echo $pagination->getLimitBox( $link ); ?>
	</div>
	<div>
		<?php echo $pagination->writePagesCounter(); ?>
	</div>
</div>
<table class="contentpaneopen<?php echo $params->get( 'pageclass_sfx' ); ?>">
<tr>
	<td>
	<?php
	foreach( $results as $result ) : ?>
		<fieldset>
			<div>
				<span class="small<?php echo $params->get( 'pageclass_sfx' ); ?>">
					<?php echo $result->count.'. ';?>
				</span>
				<?php if ( $result->href ) :
					$result->href = ampReplace( $result->href );
					if ($result->browsernav == 1 ) : ?>
						<a href="<?php echo sefRelToAbs($result->href); ?>" target="_blank">
					<?php else : ?>
						<a href="<?php echo sefRelToAbs($result->href); ?>">
					<?php endif;

					echo $result->title;

					if ( $result->href ) : ?>
						</a>
					<?php endif;
					if ( $result->section ) : ?>
						<br />
						<span class="small<?php echo $params->get( 'pageclass_sfx' ); ?>">
							(<?php echo $result->section; ?>)
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div>
				<?php echo ampReplace( $result->text );?>
			</div>
			<?php if ( !$mainframe->getCfg( 'hideCreateDate' )) : ?>
			<div class="small<?php echo $params->get( 'pageclass_sfx' ); ?>">
				<?php echo $result->created; ?>
			</div>
			<?php endif; ?>
		</fieldset>
	<?php endforeach; ?>
	</td>
</tr>
<tr>
	<td colspan="3">
		<div align="center">
			<?php $pagination->writePagesLinks( $link ); ?>
		</div>
	</td>
</tr>
</table>