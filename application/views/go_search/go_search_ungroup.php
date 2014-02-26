<?Php
############################################################################################
####  Name:             go_search_ungroup.php                                             ####
####  Type:             ci view display users individually                              ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<script src="../../../js/jquery-validate/jquery.validate.min.js"></script>
<script src="../../../js/go_search/go_search.js"></script>
<div class="group-container">
<?php
      foreach($toungroup[1] as $groups){
        $this->userhelper->individual($groups,$groupvalues);
      }
?>
</div>
