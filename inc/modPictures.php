				<div class="com">
				<?php
					//Affichage des erreurs
					switch($pictureCom){
						case 'fileMissing': echo '<p class="error">The picture is missing, please select a file.</p>';
						break;
						case 'ok': echo '<p class="succed">The picture has been uploaded!</p>';
						break;
						case 'updated': echo '<p class="succed">The description has been modified!</p>';
						break;
						case 'suppress': echo '<p class="succed">The picture has been deleted!</p>';
						break;
						case 'fileTooBig': echo '<p class="error">The file can\'t be bigger than 1.5MB!</p>';
						break;
						case 'wrongType': echo '<p class="error">The file type is not accepted, only .jpg .gif or .png supported!</p>';
						break;
						case 'existsAlready': echo '<p class="error">A file with a similar name exists already, please upload the file with a different name.</p>';
						break;
						default: echo "<p>" . $pictureCom . "</p>";
					}
				?>
				</div>
				<div class="center-left-page">
					<div class="half-subcontent">
						<h2 class="left"><?php if ($editPicture OR $pictureUpdateFieldMissing) {echo "Modify a Picture";} else echo "Upload a picture"; ?></h2>
						<form id="news" method="post" action="<?php if($editPicture OR $pictureUpdateFieldMissing) echo '?display=pictures&updatePicture=' . $pictureToBeEdited->getId(); else echo '?display=pictures';?>" enctype="multipart/form-data">
							<table>
								<tr>
									<td>
										<label for="description" class="description">Description : </label>
									</td>
									<td>
										<input type="text" name="description" style="width: 249px" id="description" value="<?php if ($editPicture) {echo str_replace("<br />", "", $pictureToBeEdited->getDescription());} else if ($fileMissing) {echo $description;} ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<label for="uploaded_image" class="uploaded_image">File (max 1.5MB) : </label>
									</td>
									<td>
      									<input type="file" id="uploaded_image" name="uploaded_image" <?php echo ($editPicture) ? 'disabled="disabled"' : ''?> />
      								</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input type="submit" value="<?php if($editPicture OR $pictureUpdateFieldMissing){echo 'Save modifications';} else {echo 'Upload';}?>" name="<?php if($editPicture OR $pictureUpdateFieldMissing){echo 'editPicture';} else {echo 'createPicture';}?>">
										<?php 
										if ($editPicture) {
										?>
										<input type="submit" value="Delete picture" name="deletePicture">
										<?php 
										}
										if($editPicture){echo '
											<a href="/admin/?display=pictures" style="margin-left: 8px;"> cancel</a>
										';}
										?>
									</td>
								</tr>
							</table>
						</form>
					</div> <!-- half-subcontent -->
					<div class="half-subcontent">
						<h2 class="left" style="margin-top: 0px; padding-top: 0px;">Gallery</h2>
						<div style="overflow-y: auto; overflow-x: hidden; height: 234px; width: 440px" >
							<?php
						
							$allPics = $picturesManager->getList();
							$i = 0;
								foreach ($allPics as $onePic) {		
								
								$onePic->setImageType("img/gallery/" . $onePic->getName());
								$onePic->setImage("img/gallery/" . $onePic->getName());
								
								$i++;
								$margin = ((is_int($i/4)) ? ' margin-right:0px;' : (($picturesManager->count() > 8) ? ' margin-right:7px;' : ' margin-right:13px;'));
								if ($onePic->getHeight() < $onePic->getWidth()) {
									$width = 474;
									$height = null;
								}
								else {
									$height = 360;
									$width = null;
								}
								
								$onMouseOver = (!$editPicture) ? "$('#preview').html('<img " . (($height != null) ? "height=&quot;".$height."&quot;" : "width=&quot;".$width."&quot;") . "src=&quot;img/gallery/" . $onePic->getName() . "&quot;>')" : "";
								$onMouseOut = (!$editPicture) ? "$('#preview').html('')" : '';
								
								echo '<div style="width: 100px; height: 100px; float: left; text-align: center; ' . $margin . ' margin-top: 4px;"><a class="gallery_picture" href="?display=pictures&editPicture=' . $onePic->getId() . '"><img style="vertical-align: bottom" onMouseOver="' . $onMouseOver . '" onMouseOut="' . $onMouseOut . '" src="img/gallery/s-' . $onePic->getName() . '"' . ' title="' . $onePic->getDescription() . '" /></a></div>';	
								
								imagedestroy($onePic->getImage());
								}		
							?>
						</div>
					</div>
				</div> <!-- centre-left-page -->					
				<div class="center-right-page">	
					<div class="subcontent">
						<h2 class="left">Preview<?php echo ($editPicture) ? " (" . $pictureToBeEdited->getShortName(30) . ")" : ""; ?></h2>
						<div class="preview" id="preview">
						<?php
								
						if ($editPicture) {
							$pictureToBeEdited->setImageType("img/gallery/" . $pictureToBeEdited->getName());
							$pictureToBeEdited->setImage("img/gallery/" . $pictureToBeEdited->getName());
							
							if ($pictureToBeEdited->getHeight() < $pictureToBeEdited->getWidth()) {
								$width = 474;
								$height = null;
							}
							else {
								$height = 360;
								$width = null;
							}
							
							echo '<img ' . (($height != null) ? 'height="'.$height.'"' : 'width="'.$width.'"') . ' src="img/gallery/' . $pictureToBeEdited->getName() . '">';
						}
						
						?>
						</div>
					</div> <!-- subcontent "modify a news" -->
				</div> <!-- center-right-page -->