<div class="modal micromodal-slide wc-sub-box-skip-shipment-modal"
     data-subscription_id="<?php echo $subscription->get_id() ?>" id="wc_sub_box_skip_shipment_popup" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true"
             aria-labelledby="modal_skip_shipment__popup_title">

            <header class="modal__header header-sticky">
                <!-- Stylish circular container for the close button -->
                <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                <h2 class="modal__title" id="modal-1-title">
                    <?php _e('Skip shipment to the next cycle',  'wc-sub-skip-shipment'); ?>
                </h2>
            </header>
            <main class="modal__content" id="modal-1-content">
                <!-- Body Section -->
                <div class="wc-sub-box-skip-shipment modal_body" >
                    <p class="wc-sub-box-skip-shipment user-pop-text">
                        <?php _e("This action will postpone your shipping and billing cycle until ", 'wc-subscriptions-customization') ?>
                        <span
                                class="wc-sub-box-skip-shipment next-shipment-date"><b><?php echo $return_set_next_date; ?></b></span>
                    </p>
                    <!-- Add any other content for the body section -->
                </div>
            </main>
            <!-- Footer Section -->
            <footer class="wc-sub-box-skip-shipment modal__footer">
                <!-- Use data-micromodal-close to close the popup -->

                <button
                        class="modal__btn wp-element-button confirm-skip-shipment"><?php _e('Confirm', 'wc-sub-skip-shipment'); ?></button>
                <button class="modal__btn wp-element-button"
                        data-micromodal-close><?php _e('Cancel',  'wc-sub-skip-shipment'); ?></button>
            </footer>

            <!-- Add any additional content for your popup here -->

        </div>
    </div>
</div>