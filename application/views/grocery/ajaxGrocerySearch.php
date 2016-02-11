<div class="col-lg-12 pagination_results">
<?php 
if(!empty($groceries)){
    
foreach ($groceries as $grocery) {
    ?>
<div class="col-lg-4">
        <div class="product">
            <div class="bg"><img src="<?php echo base_url() . '/images/deals/listing/' . $grocery->banner; ?>" class="img-responsive" alt="<?php $grocery->product_name; ?>" /></div>
            <div class="content">
                <?php
                if (!empty($grocery->deal_text)) {
                    $deal_text = explode(',', $grocery->deal_text);
                    ?>
                    <strong><?php echo $deal_text[0]; ?></strong> 
                    <?php if (isset($deal_text[1])) { ?>
                        <p><?php if (strlen($deal_text[1]) > 30) {
                echo substr($deal_text[1], 0, 30) . '...';
                            ?>
                            <?php
                            } else {
                                echo ($deal_text[1]);
                            }
                            ?>
                        </p>
                    <?php }
                    }
                    ?>
            </div>
            <div class="logo"><img src="<?php echo base_url() . '/images/deals/' . $grocery->logo; ?>" alt="<?php $grocery->brand_name; ?>" class="img-responsive" /></div>

            <div class="clearfix"></div>
        </div>
        <div class="btn_view"><a href="<?php echo base_url().'grocery/groceryDetails/'.$grocery->id?>">View Details</a></div>
        <?php $actual_link =  base_url().'grocery/groceryDetails/'.$grocery->id; 
        $image_link = base_url() . '/images/deals/listing/' . $grocery->banner;
        ?>
        
        <div class="social">
                <a   onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600,left=350,top=200');
                                                return false;" href="http://www.facebook.com/share.php?u=<?php echo $actual_link; ?>" target="_blank" class="newtooltip">

                    <img src="<?php echo base_url() . '/assets/frontend/'; ?>images/facebook.png" alt="Facebook" class="img-responsive" />
                    <span> 
                        <img class="callout" src="<?php echo base_url('images/icons/callout_black.gif'); ?>" /> 

                        Share this and get 1 SWW points from  <?php echo $grocery->shop_name; ?>
                    </span>
                </a>
                <a title="Share this and get 1 SWW points from  <?php echo $grocery->shop_name; ?>" href="http://twitter.com/intent/tweet?text=<?= urlencode('Saleswherewhen :: Online Grocery, Shop'); ?>&url=<?php echo $actual_link; ?>" target="_blank" rel="nofollow" style="background-position:-144px -16px" class="option1_16 newtooltip_tweet" title="Twitter">
                    <img src="<?php echo base_url() . '/assets/frontend/'; ?>images/twitter.png" alt="Twitter" class="img-responsive" />
                    <span> 
                        <img class="callout_tweet" src="<?php echo base_url('images/icons/callout_black.gif'); ?>" /> 

                        Share this and get 1 SWW points from  <?php echo $grocery->shop_name; ?>
                    </span>

                </a>
                <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" onclick="return sendSubscription('<?php echo $actual_link; ?>', '<?php echo $image_link; ?>', '<?php echo $grocery->id; ?>');" class="newtooltip_msg">
                    <img src="<?php echo base_url() . '/assets/frontend/images/'; ?>message.png" alt="Message" class="img-responsive" />
                    <span> 
                        <img class="callout_msg" src="<?php echo base_url('images/icons/callout_black.gif'); ?>" /> 

                        Share this and get 1 SWW points from  <?php echo $grocery->shop_name; ?>
                    </span>
                </a>
            </div>
        <div class="clearfix"></div>
    </div>
<?php } 
}else{ ?>
<p class="no_result">No Results Found</p>
<?php 

}
?>
</div>
<div class="clearfix"></div>
<div class="col-lg-12">
    <nav>
      <ul class="pagination">
        <?php
            if($page_value > 1){
                $pre = $page_value - 1;
                echo '<li><a href="#"  onclick = "return Pagination('.$pre.');"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>';
            }

            for($i = 1; $i<= $page; $i++){
                if($page_value == $i){
                    echo '<li><a href="" class = "pagi_active" onclick = "return Pagination('.$i.')">'.$i.'</a></li>';
                }else{
                    echo '<li><a href="" onclick = "return Pagination('.$i.')">'.$i.'</a></li>';
                }
            }
            if($page_value < $page){
                $next = $page_value + 1;
                echo '<li><a href=""  onclick = "return Pagination('.$next.');"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>';
            }
        ?>
      </ul>
    </nav>
    <input type = "hidden" id = "page_value" value = "<?php echo $page_value; ?>">
    <input type = "hidden" id = "page_count" value = "<?php echo $page; ?>">
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">SHARE AND EARN</h4>
            </div>
            <div class="modal-body">
                <p>Please share this deal with a friend/acquaintance or family member and earn a 1 SWW point. Enter their email address below and we'll send them this deal. </p>
                <form action="<?php echo base_url('home/email_notifications'); ?>" method="post">
                    <input type="hidden" name="item_url" id="item_url" value=""/>
                    <input type="hidden" name="image_url" id="image_url" value=""/>
                    <input type="hidden" name="item_id" id="item_id" value=""/>
                    <input type="hidden" name="current_url" id="current_url" value="<?php echo current_url(); ?>"/>
                    <div class="form-group">
                        <input type="text" class="popup_input" id="emailid" required name="emailid" placeholder="Enter your friend's email address"/>
                    </div>
                    <input type="submit" class="popup_btn"  id="subscribe_product" value="Submit"/>
                </form>
            </div>

        </div>
    </div>
</div>