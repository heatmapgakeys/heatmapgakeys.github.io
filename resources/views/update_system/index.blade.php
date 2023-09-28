@extends('layouts.auth')
@section('title',__('Settings'))
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/pages/update.css') }}">
<div class="main-content container-fluid">
	<section class="section">
		<div class="section-header">
			<h2><i class="fas fa-leaf"></i> <?php echo $page_title; ?></h2>
			<div class="section-header-breadcrumb">
				<h4><div class="breadcrumb-item">{{ __('System') }}</div></h4>
				<div class="breadcrumb-item"><?php echo "  >  " ?><?php echo  $page_title; ?></div>
			</div>
		</div>


		<?php 
			if(Session::get('success_message')==1)
			{
				echo "<div class='alert alert-success text-center'><i class='fas fa-check-circle'></i> ".__("Your data has been successfully stored into the database.")."</div>";
				Session::forget('success_message');
			}
			
			if(Session::get('warning_message')==1)
			{
				echo "<div class='alert alert-warning text-center'><i class='fas fa-warning'></i> ".__("Something went wrong, please try again.")."</div>";
				Session::forget('warning_message');
			}

			if(Session::get('error_message')==1)
			{
				echo "<div class='alert alert-danger text-center'><i class='fa fa-remove'></i> ".__("Your data was failed to stored into the database.")."</div>";
				Session::forget('error_message');
			}
				
			if(Session::get('delete_success_message')==1 || Session::get('delete_success')==1)
			{
				echo "<div class='alert alert-success text-center'><i class='fa fa-check-circle'></i> ".__("Your data has been successfully deleted from the database.")."</div>";
				Session::forget('delete_success_message');
				Session::forget('delete_success');
			}
			
			if(Session::get('delete_error_message')==1)
			{
				echo "<div class='alert alert-danger text-center'><i class='fa fa-check-circle'></i> ".__("Your was failed to delete from the database.")."</div>";	
				Session::forget('delete_error_message');
			}

			if(Session::get('payment_cancel')==1)
			{
				echo "<div class='alert alert-warning text-center'><i class='fa fa-remove'></i> ".__("Payment has been cancelled.")."</div>";
				Session::forget('payment_cancel');
			}

			if(Session::get('payment_success')==1)
			{
				echo "<div class='alert alert-success text-center'><i class='fa fa-check-circle'></i> ".__("Payment has been processed successfully. You may need a logout to affect subscription changes. It may take few minutes to appear payment in this list.")."</div>";
				Session::forget('payment_success');
			}
		?>
		<div class="section-body">
			<div class="card">
	          <div class="card-header">
	          	<h4 ><i class="fas fa-toolbox"></i>&nbsp;<?php echo config('product_short_name').' '.__("Updates");?> <code class="float-right"><?php echo __('Your Version');?> : <b>v<?php echo $current_version; ?></b></code></h4>
	          </div>
	          
	          <div class="card-body">
	          	<?php
	        		if(isset($update_versions) && count($update_versions) > 0) 
	        		{ ?>       
			        	<div class="table-responsive2">
			        		<table class='table table-bordered table-striped table-md'>
				        		<tr class='head'>
				        			<th ><?php echo __('Version');?></th>
				        			<th ><?php echo __('Change Log');?></th>
				        			<th ><?php echo __('Actions');?></th>
				        		</tr>

				        		<?php
				        		$i = 1;

				        		foreach($update_versions as $update_version)
				        		{

				        			$files_replaces = json_decode($update_version->f_source_and_replace);
				        			$sql_cmd_array = explode(';', $update_version->sql_cmd);
				        			$modal = "modal" . $i;
				        			?>		
				        			<tr class="data">
				        				<td ><div class="">v<?php echo $update_version->version; ?></div></td>
				        				<td >
				        					<button class='btn btn-outline-primary' data-bs-toggle="modal" data-bs-target="#<?php echo $modal; ?>"><i class='fa fa-eye'></i> <?php echo __('See Log');?></button>
				        					<!-- Modal -->
				        					<div class="modal fade"  tabindex="-1" id="<?php echo $modal; ?>">
				        					  <div class="modal-dialog modal-lg" role="document">

				        					    <!-- Modal content-->
				        					    <div class="modal-content">
				        					      <div class="modal-header">			        					       
				        					        <h5 class="modal-title"><?php echo $update_version->name; ?> <?php echo $update_version->version; ?> ( <?php echo __('Change Log');?> )</h5>
				        					        <button type="button" class="close" data-bs-dismiss="modal" aria-bs-label="Close">
											          <span aria-hidden="true">&times;</span>
											        </button>
				        					      </div>
				        					      <div class="modal-body-log">
													
														<?php 
															if(count($files_replaces) > 0)
															{ 	?>
																<br><h6><?php echo __('Files');?></h6>
																
																<?php 
																foreach($files_replaces as $file)
																{ ?>
																	<li><?php echo $file[1]; ?></li>
																	<?php
																}
															}
															if(count($sql_cmd_array) > 1) 
															{
																echo "<br><br><h6>".__('SQL')."</h6>";
																$j = 1;
																foreach($sql_cmd_array as $single_cmd)
																{
																	if($j < count($sql_cmd_array)) $semicolon = ';';
																	else $semicolon = '';
																	?>
																	<p><?php echo $single_cmd . $semicolon; ?></p>
																	<?php
																	$j++;
																}
															}
															else
															{
																if($update_version->sql_cmd != '')
																{
																	echo "<br><br><h6>".__('SQL')."</h6>";
																	echo "<p>" . $update_version->sql_cmd . "</p>";
																}
															} 


															echo "<br><br><h6>".__('Change Log')."</h6>";
															if($update_version->change_log!='') echo "<pre>".nl2br($update_version->change_log)."</pre>";
															else echo __('Not available');
															?>
													
				        					      </div>
				        					      <div class="modal-footer bg-whitesmoke br">
											        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal"><i class="fas fa-remove"></i> <?php echo __("Close"); ?></button>
											      </div>
				        					    </div>
				        					  </div>
				        					</div>
				        				</td>
				        				<td >
				        					<?php
				        						if($i == 1) 
				        						{ ?>
			        								<button class='btn btn-outline-primary update' updateid="<?php echo $update_version->id; ?>" version="<?php echo $update_version->version; ?>"><i class="fas fa-leaf"></i> <?php echo __('Update Now');?></button>
				        							<?php
				        						} 
				        						else
				        						{ ?>
				        							<button disabled='disabled' class='btn btn-outline-primary update' updateid="<?php echo $update_version->id; ?>" version="<?php echo $update_version->version; ?>"><i class="fas fa-leaf"></i> <?php echo __('Update Now');?></button>
				        							<?php
				        						} ?>
				        				</td>
				        			</tr>
				        			<?php
				        			$i++;
				        		}
				        	?>

			        		</table>
			        	</div>
	        			<?php	
	        		}
	        		else 
		            { ?>
		          	 <h6> <?php echo __("No update available, you are already using latest version.") ?></h6>
		          	 <?php        	
		            } ?>
	          </div>          
	        </div>
		</div>

		{{-- Modal --}}

		<div class="modal fade" tabindex="-1" role="dialog" id="update_success" data-backdrop="static" data-keyboard="false">
		  <div class="modal-dialog " role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title"><i class="fas fa-leaf"></i> <?php echo __('System Update');?></h5>
		        <button type="button" class="close" data-bs-dismiss="modal" aria-bs-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<div id="update_success_content"></div>
		      </div>
		      <div class="modal-footer bg-whitesmoke br">
		        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal"><i class="fas fa-remove"></i> <?php echo __("Close"); ?></button>
		      </div>
		    </div>
		  </div>
		</div>

	</section>
</div>
	@php
		$send_files = json_encode(array());
		$send_sql = json_encode(array());
		if(isset($update_versions[0]))
		{
			$send_files = $update_versions[0]->f_source_and_replace;
			$send_sql = json_encode(explode(';',$update_versions[0]->sql_cmd));
		}
	@endphp
@endsection

@push('scripts-footer')
<script>
	var get_img_loader = '{{ asset('assets/images/pre-loader/color/Preloader_9.gif') }}';
	var title= '{{__("Update System")}}';
	var update_msg= '{{__("You are about to update system files and database.")}}';
	var get_update_url= '{{ route("initialize-update") }}';
</script>
<script src="{{ asset('assets/js/pages/update-index.js') }}"></script>
@endpush