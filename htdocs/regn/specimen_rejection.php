<?php
#
# Main page for registering new specimen(s) in a single session/accession
#

include("redirect.php");
require_once("includes/db_lib.php");
require_once("includes/page_elems.php");
require_once("includes/script_elems.php");
$page_elems = new PageElems();
$script_elems = new ScriptElems();

LangUtil::setPageId("specimen_rejection");
putUILog('specimen_rejection', $uiinfo, basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
$script_elems->enableLatencyRecord();
$script_elems->enableJQueryForm();
$script_elems->enableAutocomplete();
$sid = $_REQUEST['sid'];
static $STATUS_REJECTED = 6;
//FUNCTION TO SAVE REJECTION REASONS
?>
<script type='text/javascript'>
function check_input()
{
	// Validate
	var reasons = $('#reasons').val();
	var referred_to = $('#referred_to_name').val();
	if(reasons == "")
	{
		alert("<?php echo "Error: Missing reasons for rejection."; ?>");
		return;
	}
	if(referred_to_name == ""){
		alert("<?php echo "Error: Missing Person talked to."; ?>");
		return;
	}
	// All OK
	$('#reject').submit();
}

</script>
<?php

//END FUNCTION
if(isset($_REQUEST['dnum']))
	$dnum = (string)$_REQUEST['dnum'];
else
	$dnum = get_daily_number();

if(isset($_REQUEST['session_num']))
	$session_num = $_REQUEST['session_num'];
else
	$session_num = get_session_number();
	
/* check discrepancy between dnum and session number and correct 
if ( substr($session_num,strpos($session_num, "-")+1 ) )
	$session_num = substr($session_num,0,strpos($session_num, "-"))."-".$dnum;
*/	
$uiinfo = "sid=".$_REQUEST['sid']."&dnum=".$_REQUEST['dnum'];
?>
<div class="tab-pane " id="tab_2">
	<div class="portlet box yellow">
		<div class="portlet-title">
			<div class="caption"><i class="icon-reorder"></i>&nbsp;&nbsp;<h4>Specimen Rejection Form</h4></div>
			
		</div>

<div class="portlet-body form">
<p style="text-align: right;"><a rel='facebox' href='#NEW_SPECIMEN'>Page Help</a></p>
<span class='page_title'><?php echo "Specimen Rejection"; ?></span>
 | <?php echo LangUtil::$generalTerms['ACCESSION_NUM']; ?> <?php echo $session_num; ?>
 | <a href='javascript:history.go(-1);'><?php echo 'Cancel'; ?></a>
<br>
<br>
<?php
# Check if Patient ID is valid
$specimen = get_specimen_by_id($sid);
if($specimen == null)
{
	?>
	<div class='sidetip_nopos'>
	<?php
	echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['SPECIMEN_ID']." ".$sid." ".LangUtil::$generalTerms['MSG_NOTFOUND']; ?>.
	<br><br>
	<a href='find_patient.php'>&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a>
	</div>
	<?php
	include("includes/footer.php");
	return;
}
?>
<?php
$main_query = mysql_query(stripslashes("SELECT DISTINCT s.specimen_id as sid, s.patient_id as patient_number, s.specimen_type_id, s.daily_num as daily_number, st.name FROM specimen s, specimen_type st WHERE s.specimen_type_id=st.specimen_type_id AND s.specimen_id=".$sid.";")) or die(mysql_error());
$main_rs = mysql_fetch_assoc($main_query);
$patient = get_patient_by_id($main_rs['patient_number']);
?>

<!-- BEGIN FORM-->
<div id="result"></div>
<form id="reject" method="post" action="accept_reject_specimen.php">
<table width="95%" border="0" class="table table-striped table-bordered table-advance table-hover">
  <tr>
    <td class="highlight"><h4>Patient ID</h4></td>
    <td><?php echo $main_rs['patient_number']; ?></td>
  </tr>
  <tr>
    <td class="highlight"><h4>Patient Number</h4></td>
    <td><?php echo $main_rs['daily_number']; ?></td>
  </tr>
  <tr>
    <td class="highlight"><h4>Patient Name</h4></td>
    <td><?php echo $patient->getName()." (".$patient->sex." ".$patient->getAgeNumber().") "; ?></td>
  </tr>
  <tr>
    <td class="highlight"><h4>Specimen Type</h4></td>
    <td><?php echo $main_rs['name']; ?></td>
  </tr>
  <tr>
    <td class="highlight"><h4>Tests</h4></td>
    <?php $sql_query = mysql_query(stripslashes("SELECT t.test_type_id, tt.name as tests FROM test t, test_type tt WHERE t.test_type_id=tt.test_type_id AND t.specimen_id=".$main_rs['sid'])) or die(mysql_error());
	 ?>
    <td><?php while($sql_rs = mysql_fetch_assoc($sql_query)){
	echo $sql_rs['tests'].'<br>';
	}?></td>
  </tr>
  <tr>
    <td class="highlight"><h4>Reasons for Rejection</h4></td>
    <input name="specimen" id="specimen" type="hidden" value="<?php echo $sid; ?>" />
    	<td>
            <textarea class="large m-wrap" rows="3" id="reasons" name="reasons"></textarea>
        </td>
    </tr>
    <tr>
        <td class="highlight"><h4>Person Talked To</h4></td>
    	<td>
            <input type='text' name='referred_to_name' id='referred_to_name' class='span4 m-wrap' />
        </td>
  </tr>
</table>
<br>
&nbsp;&nbsp;
<input type="button" class="btn yellow" name="add_button" id="add_button" onclick="check_input();" value="<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>" size="20" />
&nbsp;&nbsp;&nbsp;&nbsp;
	<a href='javascript:location.reload(false);'>&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a></form>
&nbsp;&nbsp;&nbsp;&nbsp;
<!-- END FORM-->                
										</div>
									</div>
								</div>
<div id='NEW_SPECIMEN' class='right_pane' style='display:none;margin-left:10px;'>
	<ul>
		<?php
		if(LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN']!="-") {
			echo "<li>";
			echo LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN'];
			echo "</li>";
		}	
		if(LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN_1']!="-") {
			echo "<li>";
			echo LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN_1'];
			echo "</li>";
		}	
		?>
	</ul>
</div>
<span id='progress_spinner' style='display:none;'>
	<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
</span>
<br>