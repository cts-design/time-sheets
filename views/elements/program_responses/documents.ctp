<?php if(isset($docs)) : ?>
	<div id="ProgramResponseDocs">
		<h2>Customer Provided Documents </h2>
		<?php if(is_array($docs)) :?>
			<?php foreach($docs as $doc) : ?>
				<?php if($doc['name'] != 'Rejected' && $doc['name'] != 'Deleted') : ?> 
					<div class="response-doc">
						<p><strong>Doc id:</strong> <?php echo $doc['id'] ?></p>
						<p><strong>Doc type:</strong> <?php echo $doc['name'] ?></p>
						<p><strong>Filed on:</strong> <?php echo $this->Time->format('m/d/y g:i:s a', $doc['filedDate']); ?></p>				
						<p><?php echo $doc['link'] ?></p>
					</div>
				<?php endif ?>	
			<?php endforeach ?>
			<?php foreach($docs as $doc) : ?>
				<?php if($doc['name'] == 'Rejected' || $doc['name'] == 'Deleted') : ?> 
					<div class="response-doc">
						<p><strong>Doc id:</strong> <?php echo $doc['id'] ?></p>
						<p><strong>Doc type:</strong> <?php echo $doc['name'] ?></p>
						<?php if($doc['rejectedReason'] && $doc['name'] == 'Rejected') : ?>
							<p><strong>Rejected reason:</strong> <?php echo $doc['rejectedReason'] ?></p>
						<?php endif ?>	
						<?php if(isset($doc['filedDate'])) : ?>
							<p><strong>Filed on:</strong> <?php echo $this->Time->format('m/d/y g:i:s a', $doc['filedDate']); ?></p>
						<?php endif ?>						
						<?php if(isset($doc['deletedDate'])) : ?>
							<p><strong>Deleted on:</strong> <?php echo $this->Time->format('m/d/y g:i:s a', $doc['deletedDate']); ?></p>
							<p><strong>Deleted reason:</strong> <?php echo $doc['deletedReason']; ?></p>
						<?php endif ?>	
						<p><?php echo $doc['link'] ?></p>
					</div>
				<?php endif ?>	
			<?php endforeach ?>					
		<?php else : ?>
			<?php echo $docs ?>	
		<?php endif ?>
	</div>
<?php endif ?>
<?php if(isset($generatedDocs)) : ?>
	<div id="SystemGeneratedDocs">
		<h2>System Generated Documents </h2>
		<?php foreach($generatedDocs as $generatedDoc) :?>
			<div class="system-generated-doc">
				<p><strong>Name:</strong> <?php echo $generatedDoc['name']; ?></p>
				<?php if(isset($generatedDoc['doc_id'])) : ?>
					<p><strong>Doc id:</strong> <?php echo $generatedDoc['doc_id'] ?></p> 
				<?php endif ?>
				<?php if(isset($generatedDoc['filed_on'])) : ?>
					<p><strong>Filed on:</strong> <?php echo $this->Time->format('m/d/y g:i:s a', $generatedDoc['filed_on']); ?></p>
				<?php endif ?>
				<p><?php if(isset($generatedDoc['link'])) echo $generatedDoc['link']; ?></p>
			</div>
			
		<?php endforeach ?>
	</div>
<?php endif ?>
