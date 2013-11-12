<?php require_once('header.php'); ?>
<section id="contentwrapper">  

	<div class="spaltenwrapper">
	
		<div class="linke_spalte gallery_spalte">
	
			<?php 
				
				if(isset($_GET["group_id"])):
				
					$group_id = $_GET["group_id"];
					
					$group = get_group_data($group_id);
				
					if(!$group): 
						groups_start(); 
					endif;
				
				else:
				
					groups_start();
					
				endif;
				

			if($group->privacy == "Deleted") {
				
				echo "<h3>The Group '" . $group->name . "' has been deleted.</h3>";
				
			}
			else {
		
					$members = group_get_members($group->id);
					
					// Prüfen ob $sp_user mitglied der Gruppe ist
					$is_member = group_check_member($members);	
					
					// Checken ob User Gruppenadmin ist			
					$group_admin = group_is_admin($group->admin);

			?>
	
			<div class="group_head">
			
				<a href="<?php home_path(); ?>groups/">Back to Overview</a>
				<h3><?php get_group_icon($group->privacy); ?><?php echo $group->name; ?></h3>
				<?php echo $group->description; ?>
			
				<?php if(!$is_member): ?>
				
					<button class="button_blau group_join">Join Group!</button>
					<div id="join_group_status"></div>
				
				<?php else: ?>
				
					<button class="button_blau group_join">Leave Group</button>
					
				<?php endif; ?>
			
				<?php if($group_admin): ?>
					<a href="#" id="group_setting_link">Group Settings</a>
					
					<div id="group_settings_box">
						
						<h3>Group Settings</h3>
						
						Group Description <br />
						<textarea id="group_desc" ><?php echo $group->description; ?></textarea><br />
						<br />
						Group Privacy <br />
						<select id="group_privacy">
							<option <?php if($group->privacy == "Public") echo "selected"; ?>>Public</option>
							<option <?php if($group->privacy == "Closed") echo "selected"; ?>>Closed</option>
							<option <?php if($group->privacy == "Invisible") echo "selected"; ?>>Invisible</option>
						</select>
						
						<button class="button_blau edit_group_settings" data-id="<?php echo $group->id; ?>">Save Settings</button>
						
						<button class="button_red delete_group" data-id="<?php echo $group->id; ?>">Delete Group</button>
						
					</div>
					
				<?php endif; ?>
				
				

			</div>

			// Join Requests

			<?php groups_get_topics($group->id); ?>
			
			<?php if($is_member): ?>
			<div class="group_topic_form">
				<h3>Create Topic</h3>
				
				Title
				<input id="group_topic_title" maxlenght="100" />
				Content
				<textarea id="group_topic_content"></textarea>
				<br /><br />
				<button class="group_create_topic button_blau" data-group_id="<?php echo $group->id; ?>">Create Topic</button>
				
				<div id="create_topic_status"></div>
			</div>
			<?php else: ?>
				<div>Only Members can Post.</div>
			<?php endif; ?>
			
			
		<?php } // Ende Group Deleted or Not... ? ?>
			   	
		</div>
				
		<div class="rechte_spalte start_sidebar">			
				<?php require_once('sidebar-standard.php'); ?>			
		</div>		
				
	</div>
	</div>
</section>
<?php require_once('footer.php'); ?>

<?php

function groups_get_topics($id) {

	global $dbpdo;
	global $spuser;

	$statement = $dbpdo->prepare("SELECT * FROM groups_topics WHERE group_id = :id");
	$statement->bindParam(':id', $id, PDO::PARAM_INT);	
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_OBJ);
	
	if($result) {
		
		?>
		
		<div class="group_list_head topic_list">
			<div class="gl_name">Topic</div>
			<div class="gl_posts">Posts</div>
			<div class="gl_last_post">Last Post</div>
		</div>
		
		<?php
		
		foreach ( $result as $topic):
			
			?>
			
				<div class="group_list_item topic_list">
				
					<div class="gl_name">
						<a href="<?php home_path(); ?>groups/<?php echo $topic->group_id . '/' . $topic->id; ?>">
							<?php echo $topic->title; ?>
						</a>
					</div>
					<div class="gl_posts">12</div>
					<div class="gl_last_post">12.12.1211</div>
					
				</div>
			
			<?php
				
		endforeach;
		
		
	}
	
} 

?>