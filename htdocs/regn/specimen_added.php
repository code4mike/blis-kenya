<?php
#
# Main page for showing specimen addition confirmation
# Called from new_specimen.php
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("specimen_added");

$session_num = $_REQUEST['snum'];
$script_elems->enableTableSorter();


//$session_num = get_session_current_number();
$specimen_list = get_specimens_by_session($session_num);
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->       
                        <h3>
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-download-alt"></i>
                                <a href="index.php">Home</a> 
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN REGISTRATION PORTLETS-->   
<div class="row-fluid">
    <div class="span12 sortable">
                
<div id="specimen_added">
    <div class="portlet box blue">
        <div class="portlet-title">
            <h4><i class="icon-reorder"></i><?php echo LangUtil::getTitle(); ?></h4>
            <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            </div>
        </div>
        
        <div class="portlet-body form">
        <p style="text-align: right;"><a rel='facebox' href='#Rejection'>Page Help</a></p>
            <div id='specimen_added_body'> </div>           
            
            Lab_No. <?php echo $session_num; ?>
            <?php
            if(count($specimen_list) > 1)
            {
                ?>
             | <?php echo LangUtil::$generalTerms['SPECIMEN']; ?>: <?php echo count($specimen_list); ?>
             <?php
            }
            ?>
             | <a href='session_print.php?snum=<?php echo $session_num; ?>' target='_blank'><?php echo LangUtil::$generalTerms['CMD_PRINT']; ?></a>
             | <a href='find_patient.php'>&laquo; <?php echo LangUtil::$pageTerms['MSG_NEXTSPECIMEN']; ?></a>
            <br><br>
            <?php
            if(count($specimen_list) == 0)
            {
                ?>
                <div class='sidetip_nopos'>
                    <?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['ACCESSION_NUM']." ".LangUtil::$generalTerms['INVALID']; ?>
                </div>
                <?php
                include("includes/footer.php");
                return;
            }
            $patient_id = $specimen_list[0]->patientId;
            ?>
            <table cellpadding='4px'>
                <tbody>
                    <tr valign='top'>
                        <td>
                            <?php $page_elems->getPatientInfo($patient_id); ?>
                        </td>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                            <?php $page_elems->getPostSpecimenEntryTaskList($patient_id); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <br><br>
            <small><b><?php echo LangUtil::$generalTerms['SPECIMENS']; ?></b></small>
            <table cellpadding='4px'>
                <tbody>
                    <tr valign='top'>
                    <?php
                    $count = 1;
                    foreach($specimen_list as $specimen)
                    {
                        echo "<td>";
                        echo "<div class='pretty_box'>";
                        $page_elems->getSpecimenInfo($specimen->specimenId); 
                        echo "</div>";
                        echo "</td>";
                        if($count % 2 == 0)
                        {
                            echo "</tr><tr valign='top'>";
                        }
                        $count++;
                    }
                    ?>
                    </tr>
                </tbody>
            </table>        
        </div>
    </div>
</div>
</div>
</div>
<?php include("includes/footer.php");