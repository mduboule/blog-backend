				<div class="com">
				<?php
					//Affichage des erreurs
					switch($newsCom){
						case 'fieldmissing': echo '<p class="error">The news has not been saved because one or many fields(*) were missing.</p>';
						break;
						case 'ok': echo '<p class="succed">The news has been saved !</p>';
						break;
						case 'updated': echo '<p class="succed">The news has been modified !</p>';
						break;
						case 'suppress': echo '<p class="succed">The news has been deleted !</p>';
						break;
						case 'updateFieldmissing': echo '<p class="error">You can\'t leave an empty field(*) !</p>';
						break;
						default: echo "<p></p>";
					}
				?>
				</div>
				<div class="center-left-page">
					<div class="subcontent">
						<h2 class="left"><?php if ($editNews OR $newsUpdateFieldMissing) {echo "Modify a News";} else echo "Write a news"; ?></h2>
						<form id="news" method="post" action="<?php if($editNews OR $newsUpdateFieldMissing) echo '?display=news&updateNews=' . $newsToBeEdited->getId(); else echo '?display=news';?>">
							<table>
								<tr>
									<td>
										<label for="title_news" class="news">Title<span style="color: #ff3a3a;">*</span> : </label>
									</td>
									<td>
										<input type="text" name="title_news" style="width: 249px" id="title_news" value="<?php if ($editNews) {echo str_replace("<br />", "", $newsToBeEdited->getTitle());} else if ($fieldMissing  OR $newsUpdateFieldMissing) {echo $title;} ?>" />
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;">
										<label for="content" class="content_news">Content<span style="color: #ff3a3a;">*</span> : </label>
									</td>
									<td>
										<textarea name="content_news" cols="42" rows="5" id="content_news"><?php if($editNews){echo str_replace("<br />", "", $newsToBeEdited->getContent());} else if ($fieldMissing OR $newsUpdateFieldMissing) {echo $content;} ?></textarea>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input type="submit" value="<?php if($editNews OR $newsUpdateFieldMissing){echo 'Save modifications';} else {echo 'Send';}?>" name="<?php if($editNews OR $newsUpdateFieldMissing){echo 'modifyNews';} else {echo 'createNews';}?>">
										<?php 
										if($editNews OR $newsUpdateFieldMissing){echo '
											<a href="/admin/?display=news" style="margin-left: 8px;"> cancel</a>
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
						<h2 class="left">Modify a news</h2>
						<div class="center-right-page-low">
							<table id="news">
								<tr>
									<th style="width: 4%; text-align: left;">N°</th>
									<th style="width: 20%; text-align: left;">Title</th>
									<th style="width: 55%; text-align: left;">Content</th>
									<th style="width: 15%;"></th>
								</tr>
								<?php
	
									$allNews = $newsManager->getList();
									
									if (count($allNews) < 1) {
										echo '<td colspan="3">There is no news to display, you need to create some !</td>';
									}	
									else {		
										$i = 1;
										foreach ($allNews as $oneNews) {			
											echo ($oneNews->getStatus() == 'off') ? '<tr class="white">' : '<tr>';
											
											echo '<td>';
											echo $i;
											echo '</td>';
											
											echo '<td>';
											echo str_replace("<br />", "", $oneNews->getShortTitle(12));
											echo '</td>';
		
											echo '<td>';
											echo str_replace("<br />", "", $oneNews->getShortContent2(40));
											echo '</td>';
		
											echo '<td>';
										
											echo '<a href="?display=news&toggle=' . $oneNews->getId() . '" style="vertical-align: 3px; margin-right: 4px;">';
											echo (($oneNews->getStatus() == 'on') ? 'off' : 'on') . '</a>';
											echo '<a href="?display=news&editNews=' . $oneNews->getId() . '"><img src="img/pencil.png" alt="Modify this news " height=20 width=20 alt="p" /></a>&nbsp;';
											echo '<a href="?display=news&deleteNews=' . $oneNews->getId() . '" onclick="return confirm(\'Delete this news ?\'); " ><img src="img/croix.png" alt="Suppress this news" height=20 width=20 alt="c" /></a>';
											echo '</td>';
											
											echo '</tr>';
											$i++;
										}
									}
								?>
							</table>
						</div> <!-- center-right-page-low -->
					</div> <!-- subcontent "modify a news" -->
				</div> <!-- center-right-page -->