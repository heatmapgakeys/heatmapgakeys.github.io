<div class="row">
	<div class="col-12">
		<form action="" method="POST" id="affiliate_commission_settings_form">
			<div class="card">
				<div class="card-body pb-0">

					<div class="row">
						<div class="col-12"><p class="section-title"><?php echo __('Commission Settings'); ?></p></div>
						<div class="col-12 col-md-6">
							<ul class="list-group mb-4">
								<li class="list-group-item">
									<div class="form-group mb-0">
										<div class="form-check form-switch float-start mt-2">
										    <input type="checkbox" class="form-check-input" value="1" id="by_signup_common" name="signup_commission_common" <?php if(isset($info['signup_commission']) && $info['signup_commission'] == '1') echo "checked"; else echo ""; ?>>
										    <label class="form-check-label" for="by_signup2">{{__('Signup Commission')}} 
										        <a href="#" data-bs-placement="top" data-bs-trigger="focus" data-bs-toggle="popover" title="<?php echo __("Signup Commission"); ?>" data-bs-content="<?php echo __("Affiliate will get commission on every user signup who have come through the affiliate link."); ?>"><i class='fa fa-info-circle'></i> </a>
										    </label>
										</div>
									</div>
								</li>
							</ul>

							<div class="card" id="signup_sec_div_common" >
								<div class="card-header p-3 pb-0">
									<h6><?php echo __('Signup Amount'); ?> </h6>
								</div>
								<div class="card-body p-3">
									<div class="form-group">
										<label class="mb-1" for="signup_amount_common"><i class="fas fa-briefcase"></i> <?php echo __('Amount'); ?></label>
										<div class="input-group">
											<div class="input-group-text">
												<?php echo $curency_icon?? "$"; ?>
											</div>
											<input type="text" class="form-control" name="signup_amount_common" id="signup_amount_common" value="<?php echo isset($info['sign_up_amount'])? $info['sign_up_amount']:""; ?>">
										</div>
										
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-md-6">
							<ul class="list-group mb-4">
								<li class="list-group-item">
									<div class="form-group mb-0">
										<div class="form-check form-switch float-start mt-2">
										    <input type="checkbox" class="form-check-input" value="1" id="by_payment_common" name="payment_commission_common" <?php if(isset($info['payment_commission']) && $info['payment_commission'] == '1') echo "checked";?>>
										    <label class="form-check-label" for="by_payment_common">{{__('Payment Commission')}} 
										        <a href="#" data-bs-placement="top" data-bs-trigger="focus" data-bs-toggle="popover" title="<?php echo __("Payment Commission"); ?>" data-bs-content="<?php echo __("Affiliate will get commission on every package buying package payment who have registered with the affiliate link."); ?>"><i class='fa fa-info-circle'></i> </a> 
										    </label>
										</div>

									</div>
								</li>
							</ul>

							<div class="card" id="payment_sec_div_common">
								<div class="card-header p-3 pb-0">
									<h6><?php echo __('Payment Type'); ?> </h6>
								</div>

								<div class="card-body p-3 pt-2">
									<div class="row mb-2">
										<div class="col-4">
											<div class="form-group">
												<div class="form-check form-switch float-start">
												    <input type="radio" class="form-check-input" name="payment_type_common" id="payment_type_common" value="fixed" <?php if(isset($info["payment_type"]) && $info["payment_type"]=='fixed') echo "checked";?>>
												    <label class="form-check-label" for="payment_type">{{__('Fixed')}}</label>
												</div>
											</div>
										</div>
										<div class="col-4">
											<div class="form-group">
												<div class="form-check form-switch float-start">
												    <input type="radio" class="form-check-input" name="payment_type_common" id="payment_type_common" value="percentage" <?php if(isset($info["payment_type"]) && $info["payment_type"]=='percentage') echo "checked";?>>
												    <label class="form-check-label" for="payment_type_common">{{__('Percentage')}}</label>
												</div>
											</div>
										</div>
										<div class="col-4">
											<div class="form-group">
												<div class="form-check form-switch float-start">
												    <input type="radio" class="form-check-input" name="is_recurring_common" id="is_recurring_common" value="1" <?php if(isset($info["is_recurring"]) && $info["is_recurring"]=='1') echo "checked";?>>
												    <label class="form-check-label" for="payment_type2">{{__('Recurring')}}</label>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group" id="fixed_amount_div_common" <?php if(isset($info["payment_type"]) && $info["payment_type"]=='fixed') echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
										<div class="input-group">
											<div class="input-group-text">
												<?php echo $curency_icon; ?>
											</div>
											<input type="text" class="form-control" name="fixed_amount_common" id="fixed_amount_common" value="<?php echo isset($info['fixed_amount']) ? $info['fixed_amount']:""; ?>">
										</div>
									</div>

									<div class="form-group" id="percentage_div_common" <?php if(isset($info["payment_type"]) && $info["payment_type"]=='percentage') echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
										<div class="input-group">
											<div class="input-group-text">
												<i class="fas fa-percent"></i>
											</div>
											<input type="text" class="form-control" name="percent_amount_common" id="percent_amount_common" value="<?php echo isset($info['percentage']) ? $info['percentage']:""; ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer pt-0 d-block text-center">
					<button class="btn btn-primary w-50 btn-lg" id="submit_commission"><i class="fas fa-save"></i> <?php echo __('Save'); ?></button>
				</div>
			</div>
	    </form>
	</div>
</div>