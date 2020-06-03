				<div class="com">
				<?php
					//Affichage des erreurs
					switch($blogCom){
						case 'fieldmissing': echo '<p class="error">The blog has not been saved because one or many fields(*) were missing.</p>';
						break;
						case 'ok': echo '<p class="succed">The blog has been saved !</p>';
						break;
						case 'updated': echo '<p class="succed">The blog has been modified !</p>';
						break;
						case 'suppress': echo '<p class="succed">The blog has been deleted !</p>';
						break;
						case 'updateFieldmissing': echo '<p class="error">You can\'t leave an empty field(*) !</p>';
						break;
						default: echo "<p></p>";
					}
				?>
				</div>
				<div class="center-left-page">
					<div class="subcontent">
						<h2 class="left"><?php if ($editBlog OR $blogUpdateFieldMissing) {echo "Modify a blog";} else echo "Write a blog"; ?></h2>
						<form id="blog" method="post" action="<?php if($editBlog OR $blogUpdateFieldMissing) echo '?display=blog&updateBlog=' . $blogToBeEdited->getId(); else echo '?display=blog';?>">
							<table>
								<tr>
									<td>
										<label for="title_blog" class="blog">Title<span style="color: #ff3a3a;">*</span> : </label>
									</td>
									<td>
										<input type="text" name="title_blog" style="width: 249px" id="title_blog" value="<?php if ($editBlog) {echo str_replace("<br />", "", $blogToBeEdited->getTitle());} else if ($fieldMissing  OR $blogUpdateFieldMissing) {echo $title;} ?>" />
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;">
										<label for="content" class="content_blog">Content<span style="color: #ff3a3a;">*</span> : </label>
									</td>
									<td>
										<textarea name="content_blog" cols="45" rows="12" id="content_blog"><?php if($editBlog){echo str_replace("<br />", "", $blogToBeEdited->getContent());} else if ($fieldMissing OR $blogUpdateFieldMissing) {echo $content;} ?></textarea>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input type="submit" value="<?php if($editBlog OR $blogUpdateFieldMissing){echo 'Save modifications';} else {echo 'Send';}?>" name="<?php if($editBlog OR $blogUpdateFieldMissing){echo 'modifyBlog';} else {echo 'createBlog';}?>">
										<?php 
										if($editBlog OR $blogUpdateFieldMissing){echo '
											<a href="/admin/?display=blog" style="margin-left: 8px;"> cancel</a>
										';}
										?>
									</td>
								</tr>
							</table>
						</form>
					</div> <!-- subcontent -->
				</div> <!-- centre-left-page -->					
				<div class="center-right-page">	
					<div class="subcontent">
						<h2 class="left">Modify a blog</h2>
						<div class="center-right-page-low">
							<table id="blog">
								<tr>
									<th style="width: 5%; text-align: left;">N°</th>
									<th style="width: 18%; text-align: left;">Date</th>
									<th style="width: 55%; text-align: left;">Title</th>
									<th style="width: 15%;"></th>
								</tr>
								<?php
	
									$allBlog = $blogManager->getList();
									
									if (count($allBlog) < 1) {
										echo '<td colspan="3">There is no post to be displayed, you need to create some !</td>';
									}	
									else {		
										$i = 1;
										foreach ($allBlog as $oneBlog) {			
											echo ($oneBlog->getStatus() == 'off') ? '<tr class="white">' : '<tr>';
											
											echo '<td>';
											echo $i;
											echo '</td>';
											
											echo '<td>';
											echo str_replace("<br />", "", $oneBlog->getShortDate());
											echo '</td>';
		
											echo '<td>';
											echo str_replace("<br />", "", $oneBlog->getShortTitle(40));
											echo '</td>';
		
											echo '<td>';
										
											echo '<a href="?display=blog&toggle=' . $oneBlog->getId() . '" style="vertical-align: 3px; margin-right: 4px;">';
											echo (($oneBlog->getStatus() == 'on') ? 'off' : 'on') . '</a>';
											echo '<a href="?display=blog&editBlog=' . $oneBlog->getId() . '"><img src="img/pencil.png" alt="Modify this blog " height=20 width=20 alt="p" /></a>&nbsp;';
											echo '<a href="?display=blog&deleteBlog=' . $oneBlog->getId() . '" onclick="return confirm(\'Delete this blog ?\'); " ><img src="img/croix.png" alt="Suppress this blog" height=20 width=20 alt="c" /></a>';
											echo '</td>';
											
											echo '</tr>';
											$i++;
										}
									}
								?>
							</table>
						</div> <!-- center-right-page-low -->
					</div> <!-- subcontent "modify a blog" -->
				</div> <!-- center-right-page -->