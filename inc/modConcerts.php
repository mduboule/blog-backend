				<?php
				
				$displayY = (isset($_GET["displayY"]) and $_GET["displayY"] != null) ? htmlspecialchars($_GET["displayY"]) : date('Y');
				
				?>
				<div class="com">
				<?php
				//Affichage des erreurs
					switch($concertCom){
						case 'fieldMissing': echo '<p class="error">The concert has not been saved because one or many fields(*) were missing.</p>';
						break;
						case 'ok': echo '<p class="succed">The concert has been saved !</p>';
						break;
						case 'updated': echo '<p class="succed">The concert has been modified !</p>';
						break;
						case 'suppress': echo '<p class="succed">The concert has been deleted !</p>';
						break;
						case 'concertUpdateFieldMissing': echo '<p class="error">You can\'t leave an empty field(*) !</p>';
						break;
						default: echo "<p></p>";
					}
				?>
				</div>
				<div class="center-left-page">
					<div class="subcontent">
						<h2 class="left"><?php if($editConcert){echo 'Modify a concert';} else {echo 'New concert';}?></h2>
						<form method="post" id="concert" action="<?php if($editConcert or $concertUpdateFieldMissing) echo '?display=concerts&updateConcert=' . $concertToBeEdited->getId(); else echo '?display=concerts';?>">
							<table id="form">
								<tr>	
									<td>
										<label class="concert" for="date">Date<span style="color: #ff3a3a;">*</span> :</label>
									</td>
									<td>
										<select name="day" />
											<option <?php if($day == 1)echo 'selected'; ?> value="1">1</option>
											<option <?php if($day == 2)echo 'selected'; ?> value="2">2</option>
											<option <?php if($day == 3)echo 'selected'; ?> value="3">3</option>
											<option <?php if($day == 4)echo 'selected'; ?> value="4">4</option>
											<option <?php if($day == 5)echo 'selected'; ?> value="5">5</option>
											<option <?php if($day == 6)echo 'selected'; ?> value="6">6</option>
											<option <?php if($day == 7)echo 'selected'; ?> value="7">7</option>
											<option <?php if($day == 8)echo 'selected'; ?> value="8">8</option>
											<option <?php if($day == 9)echo 'selected'; ?> value="9">9</option>
											<option <?php if($day == 10)echo 'selected'; ?> value="10">10</option>
											<option <?php if($day == 11)echo 'selected'; ?> value="11">11</option>
											<option <?php if($day == 12)echo 'selected'; ?> value="12">12</option>
											<option <?php if($day == 13)echo 'selected'; ?> value="13">13</option>
											<option <?php if($day == 14)echo 'selected'; ?> value="14">14</option>
											<option <?php if($day == 15)echo 'selected'; ?> value="15">15</option>
											<option <?php if($day == 16)echo 'selected'; ?> value="16">16</option>
											<option <?php if($day == 17)echo 'selected'; ?> value="17">17</option>
											<option <?php if($day == 18)echo 'selected'; ?> value="18">18</option>
											<option <?php if($day == 19)echo 'selected'; ?> value="19">19</option>
											<option <?php if($day == 20)echo 'selected'; ?> value="20">20</option>
											<option <?php if($day == 21)echo 'selected'; ?> value="21">21</option>
											<option <?php if($day == 22)echo 'selected'; ?> value="22">22</option>
											<option <?php if($day == 23)echo 'selected'; ?> value="23">23</option>
											<option <?php if($day == 24)echo 'selected'; ?> value="24">24</option>
											<option <?php if($day == 25)echo 'selected'; ?> value="25">25</option>
											<option <?php if($day == 26)echo 'selected'; ?> value="26">26</option>
											<option <?php if($day == 27)echo 'selected'; ?> value="27">27</option>
											<option <?php if($day == 28)echo 'selected'; ?> value="28">28</option>
											<option <?php if($day == 29)echo 'selected'; ?> value="29">29</option>
											<option <?php if($day == 30)echo 'selected'; ?> value="30">30</option>
											<option <?php if($day == 31)echo 'selected'; ?> value="31">31</option>
										</select>
										<select name="month" />
											<option <?php if($month == 1)echo 'selected'; ?> value="1">January</option>
											<option <?php if($month == 2)echo 'selected'; ?> value="2">February</option>
											<option <?php if($month == 3)echo 'selected'; ?> value="3">March</option>
											<option <?php if($month == 4)echo 'selected'; ?> value="4">April</option>
											<option <?php if($month == 5)echo 'selected'; ?> value="5">May</option>
											<option <?php if($month == 6)echo 'selected'; ?> value="6">June</option>
											<option <?php if($month == 7)echo 'selected'; ?> value="7">July</option>
											<option <?php if($month == 8)echo 'selected'; ?> value="8">August</option>
											<option <?php if($month == 9)echo 'selected'; ?> value="9">September</option>
											<option <?php if($month == 10)echo 'selected'; ?> value="10">October</option>
											<option <?php if($month == 11)echo 'selected'; ?> value="11">November</option>
											<option <?php if($month == 12)echo 'selected'; ?> value="12">December</option>
										</select>
										<select name="year" />
										<?php										
											for ($y = date('Y') - 20; $y != (date('Y') + 6); $y++) {
												echo '<option ' . (($year == $y) ? 'selected ' : "") . ((!isset($year) and $y == date('Y')) ? 'selected ' : "") . 'value="' . $y . '">' . $y . '</option>';
											}
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<label class="concert" for="concertHour">Time<span style="color: #ff3a3a;">*</span> :</label>
									</td>
									<td>
										<input type="text" name="concertHour" id="concertHour" maxlength="2" style="width: 1.4em;" value="<?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getHours()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $concertHour;} ?>" /> : <input type="text" name="concertMinute" maxlength="2" style="width: 1.4em;" value="<?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getMinutes()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $concertMinute;} ?>" />
									</td>
								</tr>
								<tr>
									<td style="padding-top: 15px;">
										<label class="concert" for="band">Band<span style="color: #ff3a3a;">*</span> :</label>
									</td>
									<td style="padding-top: 15px;">
										<input type="text" name="band" id="band" style="width: 362px;" value="<?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getBand()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $band;} ?>" />
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top; padding-top: 4px;">
										<label class="concert" for="musicians">Musicians :</label>
									</td>
									<td>
										<textarea rows="3" id="musicians" name="musicians"><?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getMusicians()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $musicians;} ?></textarea>
									</td>
								</tr>
								<tr>
									<td style="padding-top: 15px;">
										<label class="concert" for="place">Place<span style="color: #ff3a3a;">*</span> :</label>
									</td>
									<td style="padding-top: 15px;">
										<input type="text" name="place" id="place" style="width: 362px;" value="<?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getPlace()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $place;} ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<label class="concert" for="price">Price :</label>
									</td>
									<td>
										<input type="text" name="price" id="price" style="width: 362px;" value="<?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getPrice()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $price;} ?>"/>
									</td>
								</tr>
								<tr>
									<td>
										<label class="concert" for="website">Website :</label>
									</td>
									<td>
										<input type="text" name="website" id="website" style="width: 362px;" value="<?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getWebsite()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $website;} else echo 'http://'; ?>" />
									</td>
								</tr>
								
								<tr>
									<td style="vertical-align: top; padding-top: 4px;">
										<label class="concert" for="details">Details :</label>
									</td>
									<td>
										<textarea cols="30" rows="3" id="details" name="details"><?php if ($editConcert) {echo stripslashes(str_replace("<br />", "", $concertToBeEdited->getDetails()));} else if ($concertFieldMissing  OR $concertUpdateFieldMissing) {echo $details;} ?></textarea>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input type="submit" id="submit" value="<?php if($editConcert){echo 'Update';}else {echo 'Send';}?>" name="<?php if($editConcert OR $concertUpdateFieldMissing){echo 'updateConcert';} else {echo 'createConcert';}?>" />
										<?php 
										if($editConcert OR $concertUpdateFieldMissing){echo '
											<a href="/admin/?display=concerts" style="margin-left: 8px;"> cancel</a>
										';}
										?>
									</td>
								</tr>
							</table>
						</form>
					</div> <!-- subcontent -->
				</div> <!-- center-left-page -->
				<div class="center-right-page">	
					<div class="subcontent">
						<?php
						
						echo ($concertsManager->countByYear($displayY - 1) > 0) ? '<a class="navY" href="?display=concerts&displayY=' . ($displayY - 1) . '">&lt;</a>' : '<span class="emptyY">&lt;</span>';
						echo '<h2 class="nav">' . $displayY . '</h2>';
						echo ($concertsManager->countByYear($displayY + 1) > 0) ? '<a class="navY" href="?display=concerts&displayY=' . ($displayY + 1) . '">&gt;</a>' : '<span class="emptyY">&gt;</span>';

						?>
						<h2 class="left" style="margin-bottom: 0;">Modify a concert</h2>
						<div class="center-right-page-low">
							<table id="news" style="width: 100%; margin-top: 13px;">
								<tr>
									<th style="width: 19%; text-align: left;">Date</th>
									<th style="width: 40%; text-align: left;">Band</th>
									<th style="width: 29%; text-align: left;">Place</th>
								</tr>
								<?php
	
									$allConcerts = $concertsManager->getListByYear($displayY);
									
									if (count($allConcerts) < 1) {
										echo '<td colspan="3">There is no concerts to display, you need to create some !</td>';
									}	
									else {		
										foreach ($allConcerts as $oneConcert) {		
											
											if ($oneConcert->getYear() < date('Y')) {					
												echo '<tr class="white">';
											}
											else if ($oneConcert->getYear() == date('Y')) {
												if ($oneConcert->getMonth() < date('m')) {
													echo '<tr class="white">';
												}
												else if ($oneConcert->getMonth() == date('d')) {
													if ($oneConcert->getDay() < date('d')) {
														echo '<tr class="white">';
													}
													else {
														echo '<tr>';
													}
												}
												else {
													echo '<tr>';
												}
											}
											else {
												echo '<tr>';
											}
											
											echo '<td>';
											echo $oneConcert->getFullDate2();
											echo '</td>';
		
											echo '<td>';
											echo stripslashes(str_replace("<br />", "", $oneConcert->getShortBand(21)));
											echo '</td>';
		
											echo '<td>';
											echo stripslashes($oneConcert->getShortPlace(16));
											echo '</td>';
		
											echo '<td>';
											echo '<a href="?display=concerts&displayY=' . $displayY .'&editConcert=' . $oneConcert->getId() . '"><img src="img/pencil.png" alt="Modify this concert " height=20 width=20 alt="p" /></a>&nbsp;';
											echo '<a href="?display=concerts&displayY=' . $displayY .'&deleteConcert=' . $oneConcert->getId() . '" onclick="return confirm(\'Delete this concert ?\'); " ><img src="img/croix.png" alt="Suppress this concert" height=20 width=20 alt="c" /></a>';
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