<?php
/** 
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */
?>
                <div class="testimonial">
                    <p>TBWA understood our needs and did a great job helping us to find the right person.
                    In the furture, when we need to hire again, we will be coming back to Tampa Bay WorkForce Alliance
                    for their great service.</p>
                    <p class="signed">- Paul Thompson <span>UC Davis, Inc</span></p>
                </div> <!-- end .testimonial -->
                <div class="clear"></div>
                
                <div class="feedback">
                    <h3>Tell Us What You Think</h3>
                    <p>Overall, in terms of resources and information, how effective is this website in meeting your employment
                    needs?</p>
                    <form action="#" method="post">
                        <p>
                            <input type="radio" value="Very Effective" name="very_effective" />
                            <label for="very_effective">Very Effective</label>
                        </p>
                        <p>
                            <input type="radio" value="Somewhat Effective" name="somewhat_effective" />
                            <label for="somewhat_effective">Somewhat Effective</label>
                        </p>
                        <p>
                            <input type="radio" value="not_very_effective" name="not_very_effective" />
                            <label for="very_effetive">Not Very Effective</label>
                        </p>
                        <p><input type="submit" id="feedback_submit" value="Select" /></p>
                    </form>
                </div> <!-- end .feedback -->
				
                <div class="employers">
                    <h3>Employers</h3>
                    <?php echo $this->Nav->links('Employers'); ?>
                </div> <!-- end .employers -->

                <div class="career_seekers">
                    <h3>Career Seekers</h3>
                    <?php echo $this->Nav->links('Career Seekers'); ?>
                </div> <!-- end .career_seekers -->

                <div class="programs">
                    <h3>Programs</h3>
					<?php echo $this->Nav->links('Programs');?>
                </div> <!-- end .programs -->

                <div class="links">
                    <ul>
                        <li><a href="#">Click here</a> for our Calendar of Events</li>
                        <li><a href="#">Click here</a> to view your nearest OneStop Center</li>
                        <li class="last"><a href="#">Click here</a> for our Calendar of Events</li>
                    </ul>
                </div> <!-- end .links -->
                <div class="clear"></div>
                <div class="terms">
                    <p>TBWA REGISTRATION - Tampa Bay WorkForce Alliance is required to maintain a record of information
                    about anyone participating in our online services. To make these services simple to use, we insure that
                    all active visitors to our website register online with us first. If you have used any of our services
                    on our previous website or at our One-Stop Centers, there is a possibility you are already in our system.
                    Please use the “Account Login” link at the top of this page to login before you try to create an account.
                    After you have registered with us, you will only need to login when completing online services for our
                    system to identify you. All of your personal information is secure and we do not share your personal
                    information with any third parties.</p>
                </div> <!-- end .terms -->