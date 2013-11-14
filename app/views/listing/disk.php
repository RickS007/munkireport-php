<?$this->view('partials/head')?>

<? //Initialize models needed for the table
new Machine;
new Disk_report_model;
new Reportdata;
?>

<div class="container">

  <div class="row">

  	<div class="col-lg-12">
		<script type="text/javascript">

		$(document).ready(function() {

				// Get modifiers from data attribute
				var myCols = [], // Colnames
					mySort = [], // Initial sort
					hideThese = [], // Hidden columns
					col = 0; // Column counter

				$('.table th').map(function(){

					  myCols.push({'mData' : $(this).data('colname')});

					  if($(this).data('sort'))
					  {
					  	mySort.push([col, $(this).data('sort')])
					  }

					  if($(this).data('hide'))
					  {
					  	hideThese.push(col);
					  }

					  col++
				});

			    oTable = $('.table').dataTable( {
			        "bProcessing": true,
			        "bServerSide": true,
			        "sAjaxSource": "<?=url('datatables/data')?>",
			        "aaSorting": mySort,
			        "aoColumns": myCols,
			        "aoColumnDefs": [
			        	{ 'bVisible': false, "aTargets": hideThese }
					],
			        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
			        	// Update name in first column to link
			        	var name=$('td:eq(0)', nRow).html();
			        	if(name == ''){name = "No Name"};
			        	var sn=$('td:eq(1)', nRow).html();
			        	var link = get_client_detail_link(name, sn, '<?=url()?>/');
			        	$('td:eq(0)', nRow).html(link);
			        	
			        	// is SSD ?
			        	var status=$('td:eq(4)', nRow).html();
			        	status = status == 1 ? '<span class="label label-success">Yes</span>' : 
			        		(status === '0' ? 'No' : '')
			        	$('td:eq(4)', nRow).html(status)

			        	// Format disk usage
			        	var disk=$('td:eq(5)', nRow).html();
			        	var cls = disk > 90 ? 'danger' : (disk > 80 ? 'warning' : 'success');
			        	$('td:eq(5)', nRow).html('<div class="progress"><div class="progress-bar progress-bar-'+cls+'" style="width: '+disk+'%;">'+disk+'%</div></div>');
			        	
			        	// Format filesize
			        	var fs=$('td:eq(6)', nRow).html();
			        	$('td:eq(6)', nRow).addClass('text-right').html(fileSize(fs, 0));

			        	// Format filesize
			        	var fs=$('td:eq(7)', nRow).html();
			        	$('td:eq(7)', nRow).addClass('text-right').html(fileSize(fs, 0));

				    }
			    } );
			} );
		</script>

		  <h3>Disk report <span id="total-count" class='label label-primary'>…</span></h3>

		  <table class="table table-striped table-condensed table-bordered">
		    <thead>
		      <tr>
		      	<th data-colname='machine#computer_name'>Name</th>
		        <th data-colname='machine#serial_number'>Serial</th>
		        <th data-colname='reportdata#long_username'>Username</th>
		        <th data-colname='machine#machine_name'>Type</th>
		        <th data-colname='diskreport#SolidState'>Solid state</th>
		        <th data-sort='desc' data-colname='diskreport#Percentage'>Disk</th>
		        <th data-colname='diskreport#FreeSpace'>Free</th>
		        <th data-colname='diskreport#TotalSize'>Size</th>
		    	<th data-colname='diskreport#SMARTStatus'>SMART</th>
		      </tr>
		    </thead>
		    <tbody>
		    	<tr>
					<td colspan="5" class="dataTables_empty">Loading data from server</td>
				</tr>
		    </tbody>
		  </table>
    </div> <!-- /span 12 -->
  </div> <!-- /row -->
</div>  <!-- /container -->

<?$this->view('partials/foot')?>