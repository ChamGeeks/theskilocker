<div id="dh_ptp_tabs_1" class="dh_ptp_tab">
    <h4><?php _e('Pricing Table Columns', PTP_LOC); ?></h4>
    <a href="#" style="float:right;" id="ptp-new-column" class="docopy-column button button-large ptp-icon-plus"><?php _e('New Column', PTP_LOC); ?></a>
    <div style="clear: both; margin-bottom:10px;"></div>

    <div id="all-columns-wrap">
        <div class="column zero">
            <div class="ptp-title explaination-title">
                <strong><?php _e('Plan Name', PTP_LOC); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Plan Name', PTP_LOC);?>&lt;/strong&gt;" data-content="
				            <?php _e('Enter your pricing plan - names here.', PTP_LOC); ?>
				            &lt;br/&gt;&lt;br/&gt; 
				            &lt;strong&gt;<?php _e('Best practice:', PTP_LOC); ?>&lt;/strong&gt;
				            &lt;br/&gt;
				            <?php echo htmlspecialchars( __('Avoid generic names such as <em>Bronze</em>, <em>Silver</em> and <em>Gold</em>.<br/>', PTP_LOC)); ?>
				            <?php echo htmlspecialchars( __('Instead, choose aspirational names like <em>Personal</em>, <em>Small Business</em> and <em>Enterprise</em>.<br/><br/>', PTP_LOC)); ?>
				            <?php echo htmlspecialchars( __('Many people choose plans based on names, not features: A corporate buyer might choose <em>Enterprise</em> even though <em>Personal</em> might be sufficient for his use-case.', PTP_LOC)); ?>
				            "></i>
            </div>
            <ul class="ptp-settings explaination-settings">
                <li class="explaination-desc">
                    <strong><?php _e('Pricing', PTP_LOC); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Pricing', PTP_LOC); ?>&lt;/strong&gt;" data-content="
					            <?php _e('Enter your pricing options here.', PTP_LOC); ?>
					            "></i>
                </li>
                <li class="features explaination-desc">
                    <strong><?php _e('Plan Features', PTP_LOC); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Features', PTP_LOC); ?>&lt;/strong&gt;" data-content="
					            <?php _e('Enter your features here (one per line).', PTP_LOC); ?>
					            &lt;br/&gt;&lt;br/&gt; 
					            &lt;strong&gt;<?php _e('Best practice:', PTP_LOC); ?>&lt;/strong&gt;
					            &lt;br/&gt;
					            <?php _e("Don't overwhelm users with too much content. Long pricing tables are confusing and difficult to read.", PTP_LOC); ?>
					            "></i>
                </li>
                <li class="explaination-desc">
                    <strong><?php _e('Button Text', PTP_LOC); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Call To Action Text'); ?>&lt;/strong&gt;" data-content="
					            <?php _e('Enter your call to action text here.', PTP_LOC); ?>
					            &lt;br/&gt;&lt;br/&gt; 
					            &lt;strong&gt;<?php _e('Best practice:', PTP_LOC); ?>&lt;/strong&gt;
					            &lt;br/&gt;
					            <?php _e('Here are some of the highest converting variations:', PTP_LOC); ?>;&lt;br/&gt; 
					            * <?php _e('Add To Cart', PTP_LOC); ?>&lt;br/&gt; 
					            * <?php _e('Sign Up', PTP_LOC); ?>&lt;br/&gt; 
					            * <?php _e('Sign Up Free', PTP_LOC); ?>&lt;br/&gt; 
					            * <?php _e('Start Free Trial', PTP_LOC); ?>"></i>
                </li>
                <li class="explaination-desc">
                    <strong><?php _e('Button URL', PTP_LOC); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Call To Action URL', PTP_LOC); ?>&lt;/strong&gt;" data-content="
					            <?php _e('Enter your call to action URL here. This is usually either a payment link (e.g. PayPal) or a page where users can create an account.', PTP_LOC); ?>
					            "></i>
                </li>
            </ul>
        </div>

        <?php
          /*
        * check if this pricing table is already exist will return number of column as user did setting
        * otherwise if not exist yet, will initialize 2 columns  
        */ 
//        $checkIsExistMeta = get_post_meta(get_the_ID(), $id, TRUE);
        if ($meta) {
                         $options = array();
               
        } else {
            $options = array('length' => 2);
        }
        /**
         * the loop used to display our tables
         */
        while($mb->have_fields_and_multi('column',array('length' => 2))):
            ?>
            <?php $mb->the_group_open(); ?>
            <div class="column">

                <div class="ptp-title plan-title">

                    <?php $mb->the_field('feature');?>
                    <input type="hidden" name="<?php $mb->the_name(); ?>" value="<?php if(!is_null($metabox->get_the_value())){$mb->the_value();} elseif(!$mb->is_last()){ echo "unfeatured"; }?>" class="form-control" />

                    <a onClick="buttonHandler(this)" class="button button-small feature-button <?php if($mb->get_the_value()=="featured"){echo "ptp-icon-star";}else {echo "ptp-icon-star-empty";}?>" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Feature This Column', PTP_LOC); ?>&lt;/strong&gt;" data-content="<?php _e("Click this button to feature this column. A featured column appears bigger and includes the wording 'Most Popular'. You can only feature one column per table.", PTP_LOC); ?>"><?php _e('Feature', PTP_LOC); ?></a>
                    <button class="button button-small dodelete ptp-icon-trash" id="delete-button" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Delete This Column', PTP_LOC); ?>&lt;/strong&gt;" data-content="<?php _e('Click this button to delete this column.', PTP_LOC); ?>"><?php _e('Delete', PTP_LOC); ?></button>

                    <?php $mb->the_field('planname');?>
                    <input id="plan-name" type="text" name="<?php $mb->the_name(); ?>" placeholder="<?php _e('e.g. Small Business', PTP_LOC); ?>" value="<?php echo $mb->the_value(); ?>" class="form-control">
                </div>

                <ul class="ptp-settings plan-settings">
                    <li>
                        <?php $mb->the_field('planprice'); ?>
                        <input type="text" name="<?php $mb->the_name(); ?>" placeholder="<?php _e('e.g. $49/mo', PTP_LOC); ?>" value="<?php echo $mb->the_value(); ?>" class="form-control">
                    </li>
                    <li class="features">
                        <?php $mb->the_field('planfeatures'); ?>
						<?php
							$placeholder = array(
								str_pad(__('e.g. 1 Website', PTP_LOC), 40),
								str_pad(__('30,000 Monthly Visits', PTP_LOC), 40),
								str_pad(__('Unlimited Data Transfer', PTP_LOC), 40),
								str_pad(__('5GB Storage', PTP_LOC), 40),
							);
						?>
                        <textarea name="<?php $mb->the_name(); ?>" class="form-control" rows="7" placeholder="<?php echo implode('', $placeholder); ?>"><?php echo $mb->the_value(); ?></textarea>
                    </li>

                    <li>
                        <?php $mb->the_field('buttontext'); ?>
                        <input type="text" pla name="<?php $mb->the_name(); ?>" placeholder="<?php _e('e.g. Start A Free Trial', PTP_LOC); ?>" value="<?php  $mb->the_value(); ?>" class="form-control">
                    </li>

                    <li>
                        <?php $mb->the_field('buttonurl'); ?>
                        <input type="text" placeholder="<?php _e('e.g. http://example.com/buy', PTP_LOC); ?>" name="<?php $mb->the_name(); ?>" value="<?php echo $mb->the_value();?>" class="form-control">
                    </li>
                </ul>
            </div>
            <?php $mb->the_group_close(); ?>
        <?php endwhile; ?>
		<div style="clear:both;"></div>
    </div>
</div>