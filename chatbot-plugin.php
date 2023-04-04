<?php

/*
Plugin Name: Chatbot plugin
Plugin URI:
Description: This is chatbot support plugin.
Version: 1.0.0
Author: Alex Arashi
Author URI:
*/

function chatbot_plugin() {
	$key = get_option( 'OPENAI_API_KEY' );
	?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script type="module" src="./node_modules/openai/index.ts"></script>
			<link href="<?php echo plugin_dir_url( __FILE__ ) . 'css/chatbot.css'; ?>" rel="stylesheet"/>
			<script>
				var greeting_done = false;
				var goods_information;
				var topic_now;
				var categories_in_this_store;

				var key = '<?php echo $key;?>'

				var answer_return = 
					[
						'Our return policy offers a 7-day no-question-asked exchange period. Please note that the product and packaging must be in reasonable condition with all accessories included. You can find more details on our return policy page at https://www.zakka.com.au/return-refund-policy/.',
						'We have a 7-day no-question-asked exchange period. The product and packaging must be in reasonable condition with all accessories included. Please visit our return policy page at https://www.zakka.com.au/return-refund-policy/ for more information.',
						'You have a 7-day no-questions-asked exchange period for items that are in reasonable condition with all the accessories included. You can find more information on our return policy page: https://www.zakka.com.au/return-refund-policy/',
						'Our return policy includes an exchange period of 7 days for items that are in reasonable condition with all the accessories included. You can find more information on our return policy page: https://www.zakka.com.au/return-refund-policy/',
						'We have a 7-day exchange period, but the product and packaging must be in reasonable condition with all the accessories. For more details, please visit our Return and Refund Policy page at https://www.zakka.com.au/return-refund-policy/.',
						'We only accept returns for items within the 7-day exchange period. You can find more information on our return policy page: https://www.zakka.com.au/return-refund-policy/',
						'Yes, you will need to cover the cost of return shipping. You can find more information on our return policy page: https://www.zakka.com.au/return-refund-policy/',
						'To initiate a return, please contact our customer service team within 7 days of purchase. You can find more information on our return policy page: https://www.zakka.com.au/return-refund-policy/',
						'If you receive a defective item, please contact our customer service team within 7 days of purchase. You can find more information on our return policy page: https://www.zakka.com.au/return-refund-policy/',
						'There is no fee for returning a product. However, the product and packaging must be in reasonable condition with all the accessories. We have a 7-day exchange period. For more details, please visit our Return and Refund Policy page at https://www.zakka.com.au/return-refund-policy/.'
					]
				
				var answer_security = 
					[
						'Absolutely. We use eWay payment gateway which is known for its robust security features and our site is encrypted with SSL.',
						'Our website is completely secure for online transactions. We use eWay payment gateway which is very secure and SSL encryption.',
						'Your information is completely safe with us. We use eWay payment gateway, a leading payment processor known for its robust security.',
						'We take the safety of your payment very seriously. Our payment processing is handled by eWay, which is a highly secure gateway.',
						'We take data protection seriously. We use eWay payment gateway with advanced fraud protection feature and our site is SSL-encrypted.',
						'We use eWay payment gateway which has advanced fraud protection feature and SSL encryption to ensure your payment information is safe.',
						'We guarantee that your payment will be secure. We use eWay payment gateway, a secure and reliable payment processor and SSL encryption.',
						'We take payment security very seriously. We use eWay payment gateway which provides advanced fraud protection feature and SSL-encryption.',
						'You can be assured that your transaction is secure with us. We use eWay payment gateway that includes fraud protection features and our site has SSL encryption.',
						'We take the security of your information very seriously. We use eWay payment gateway that includes fraud protection features and our site has SSL encryption.'
					]

				var answer_afterpay = 
					[
						'Afterpay is a buy now, pay later service that allows you to split your payment into four installments, interest-free. If you do not have an account with them yet, you will need to sign up on their website first.',
						'Afterpay is a service that lets you buy what you want today and pay for it later in four equal installments, interest-free. To use it, you will need to sign up with Afterpay first.',
						'Afterpay allows you to split your payment into four installments, with the first payment due at the time of purchase and the remaining three payments due every two weeks. There is no interest or fees, but you will need to sign up with Afterpay first.',
						'Afterpay is a payment option that lets you split your payment into four installments, with the first payment due at the time of purchase and the remaining three payments due every two weeks. To use it, you will need to sign up with Afterpay first.',
						'With Afterpay, you can buy what you want today and pay for it later in four equal installments, interest-free. It is a great way to budget your purchases and avoid interest charges. If you do not have an account with them yet, you will need to sign up on their website first.',
						'To sign up for Afterpay, simply go to their website and create an account. You will then be able to use it as a payment option at checkout, allowing you to split your payment into four installments, interest-free.',
						'Afterpay is available for most of our products. It is a buy now, pay later service that allows you to split your payment into four installments, interest-free. If you do not have an account with them yet, you will need to sign up on their website first.'
					]

				var answer_paypal_fees = 
					[
						'No, there is no additional surcharge for using Paypal as a payment method on our website. But please note that Paypal may charge a transaction fee.',
						'We do not charge any extra surcharge for using Paypal. However, please note that Paypal may charge a transaction fee depending on the payment method used.',
						'No, we do not add any additional surcharge for using Paypal as a payment method on our website. But please note that Paypal may charge a transaction fee.',
						'No, there are no additional surcharges or hidden fees for using Paypal as a payment method on our website. But please note that Paypal may charge a transaction fee.',
						'No, using Paypal as a payment method does not incur any additional surcharge on our website. However, please note that Paypal may charge a transaction fee.',
						'No, you will not be charged any extra fees for using Paypal as a payment method. However, please note that Paypal may charge their own transaction fees.',
						'No, there will be no extra charge for using Paypal. However, please note that Paypal may charge their own transaction fees.',
						'Yes, you can use Paypal as a payment method without incurring any extra charges from us. But, please keep in mind that Paypal may charge their own transaction fees.'
					]

				var answer_fees = 
					[
						'No, we do not charge any extra fee for using credit or debit cards.',
						'No, we do not charge any additional fee for paying with a credit or debit card.',
						'No, there is no fee for using a credit or debit card on our website.',
						'No, we do not charge extra for using a credit or debit card to make a purchase.',
						'No, you do not need to pay any additional charges for using a credit or debit card to make a purchase.',
						'No, there are no additional fees for using a debit card to pay for your order.',
						'No, there are no additional fees or surcharges for using credit or debit cards as payment.',
						'No, you will not be charged any additional fees for using a credit or debit card for payment.'
					]

				var answer_debit_card = 
					[
						'Yes, we accept all debit cards issued by banks affiliated with Mastercard and Visa.',
						'We accept all major credit and debit cards, including those issued by banks affiliated with Mastercard and Visa.',
						'You can easily manage your expenses and avoid incurring debt.',
						'With a debit card, the payment is deducted directly from your bank account, while with a credit card, you borrow money from the bank to pay for your purchases.',
						'As long as it is issued by a bank affiliated with Master or Visa, there are no restrictions on using a debit card for payment.',
					]

				var answer_payment_method =
					[
						'We accept all major credit cards, as well as Afterpay and Paypal, so you can choose the option that works best for you.',
						''
					]

				var answer_international_shipping = 
					[
						'We currently do not offer international shipping, but we will let you know if we introduce these services in the future.',
						'Unfortunately, we do not currently offer international shipping. However, we will notify you if this changes in the future.',
						'Currently, we do not offer international shipping, but we will let you know if we introduce these services in the future.'
					]

				var answer_express_shipping = 
					[
						'We do not offer expedited or express shipping options at this time, but we will keep you updated if we introduce these services in the future.',
						'Unfortunately, we do not offer expedited or express shipping options at this time, but we will keep you posted',
						'At this time, our standard shipping is the only option available, as we do not currently offer express shipping.',
						'We do not currently offer same-day or express shipping options, but we will keep you posted if we add these options in the future.',
						'We have a flat rate of $10 for orders below $150, and shipping is free for orders above $150, as we do not offer express or faster shipping options.',
						'Our standard shipping typically takes 2-3 business days for items in stock, as we do not offer express or faster shipping options.',

					]

				var answer_shipping_cost = 
					[
						'Our standard shipping rate is a flat $10 for orders below $150, and shipping is free for orders above $150.',
						'We have a flat shipping rate of $10 for orders below $150, and shipping is free for orders above $150.',
						'Most orders have a flat shipping rate of $10 for orders below $150, and shipping is free for orders above $150.',
						'We offer a flat shipping rate of $10 for orders below $150 and free shipping for orders above $150.',
						'The shipping cost for your order would be a flat rate of $10 for orders below $150 and free for orders above $150.'
					]

				var answer_shipping_time = 
					[
						'The average shipping time for most orders is 2-3 business days for items in stock.',
						'The average delivery time for orders is 2-3 business days for items in stock.',
						'Our typical shipping duration is 2-3 business days for items in stock.',
						'Orders typically ship out within 1-2 business days, and delivery takes an additional 2-3 business days for items in stock.',
						'Based on our standard shipping method, your order should arrive within 2-3 business days for items in stock.'
					]

				var answer_shipping = 
					[

					]
				
				var answer_find_product = 
					[
						'Sure, do you remember the name of the product or any keywords that could help me find it?',
						'Of course, what is the name of the product you are looking for?',
						'Absolutely, what is the name of the product you are looking for?',
						'Yes, I can definitely guide you through the process. What is the name of the product you are looking for?',
						'Yes, we have a range of products that are made locally or support a cause. What type of product are you looking for?',
					]

				var answer_tracking =
					[
						'To know status of your package, you can use the tracking number that was sent to you in the email confirmation after you initiated the exchange.'
					]

				var answer_order_status =
					[

					]

				var answer_about_us =
					[
						'Our products are unique and stylish, setting them apart from others on the market. We strive to provide anything trendy, cool and practical to improve your lifestyle.',
						'Our website features a wide range of lifestyle products including unique and stylish gadgets, home products, and more. We strive to provide anything trendy, cool and practical to improve your lifestyle.',
						'Our unique product offerings include a wide range of stylish and practical gadgets, home products, and lifestyle items. We strive to provide anything trendy, cool and practical to improve your lifestyle.'
					]
				
				var answer_location =
					[
						'We do not have a retail store for customers to buy our products. We are an online-only operation.',
						'We are an online-only operation, and our products are available for purchase exclusively through our website.',
						'Our products are available exclusively through our website. We do not have a physical store or retail location for customers to purchase products.',
						'We do not have any retail stores that carry our products. We are an online-only operation.',
						'We do not have any physical locations where customers can see our products in person. They are available for viewing exclusively on our website.'
					]

				var answer_contact = 
					[
						'The best ways to contact our support team are by phone at 1300 133 692 or email at cs@zakka.com.au.',
						'You can get assistance by calling our customer service at 1300 133 692 or emailing cs@zakka.com.au.',
						'You can contact our customer service at 1300 133 692 or email cs@zakka.com.au.',
						'Our customer care can be reached at 1300 133 692 and by email at cs@zakka.com.au.',
						'The best way to get in touch with our customer service is by calling 1300 133 692 or emailing cs@zakka.com.au.'
					]

				var answer_hours =
					[
						'Our customer service hours are Monday-Friday, 9am to 6pm.',
						'Our support team is available Monday-Friday, 9am to 6pm.',
						'You can reach our customer service team Monday-Friday, 9am to 6pm.',
						'Our help desk hours are Monday-Friday, 9am to 6pm.',
						'The hours of operation for our support team are Monday-Friday, 9am to 6pm.',
						'The best time to contact our customer service is Monday-Friday, 9am to 6pm.',
						'You should call our customer support Monday-Friday, 9am to 6pm.',
						'You can get support from our customer service staff Monday-Friday, 9am to 6pm.'
					]

				var answer_greetings_set =
					[
						'Good day! How can I assist you today?',
						'Hello! I am here to provide support. What do you need assistance with?',
						'Hey! I would be happy to assist you. What do you need help with?',
						'Hello! I am here to assist you. What do you need help with?',
						'Hey! I would be happy to guide you. What do you need help with?'
					]

				$(document).ready(function(){

					function sleep(ms) {
						return new Promise(resolve => setTimeout(resolve, ms));
					}

					$.ajax({
						type: "GET",
						url: "<?php echo plugin_dir_url( __FILE__ ) . 'csvs/zakka.csv'; ?>",
						success: function(data) {processData(data);}
					});

					function processData(allText) {
						var allTextLines = allText.split(/\r\n|\n/);
						var headers = allTextLines[0].split(',');
						var lines = [];

						for (var i=1; i<allTextLines.length; i++) {
							var data = allTextLines[i].split(',');
							if (data.length == headers.length) {

								var tarr = [];
								for (var j=0; j<headers.length; j++) {
									tarr.push(headers[j]+":"+data[j]);
								}
								lines.push(tarr);
							}
						}
						goods_information = lines;
					}

					function get_goods_names() {
						let goods_names = [];
						for (let i=0; i<goods_information.length; i++) {
							goods_names.push(goods_information[i][6].split(':')[1]);
						}
						return goods_names;
					}

					function get_goods_names_string() {
						let goods_names = get_goods_names()
						let goods_names_string = ''
						for (let i=0; i<goods_names.length; i++) {
							goods_names_string += goods_names[i] + ','
						}
						goods_names_string = goods_names_string.substring(0, goods_names_string.length - 1)
						return goods_names_string
					}

					$(".cb-close-img").css("height", "0px");
					$('.cb-container-no-touch').hide();	
					$('#cb-open-btn').click(function(){
						// if (window.innerWidth <= 600) {
						// 	$('#cb-close-btn-responsive').show()
						// }
						$('.cb-container-no-touch').animate({
								 height: "toggle",
							}, 1000, function() {
						});
						$('#cb-open-btn').animate({
								opacity: '.5' ,	 
								height: "toggle",
							}, 1000, function() {
							});
						$('#cb-close-btn').animate({
								opacity: '1' ,	 
								height: "50px",
							}, 1000, function() {
							});
						if (greeting_done == false) {
							sleep(1000).then(() => {
								var reply_text = 'Hi! How can I help you today?';
								var append_html = 
									'<div class="cb-div">' +
										'<div class="cb-bot-icon-div">' +
											'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
										'</div>' +
										'<div class="cb-message-div">' +
											'<span class="cb-bot-message-span">' + reply_text + '</span>' +
										'</div>' + 
									'</div>';
								$('.cb-content').append(append_html);
								greeting_done = true;
							});
						}
					});

					$("#cb-close-btn").click(function(){
						$('.cb-container-no-touch').animate({
								 height: [ "toggle", "swing" ],
							}, 500, function() {
							});
						$('#cb-close-btn').animate({
							opacity: '.5' ,	 
							height: "0px",
							}, 1000, function() {
							});
						$('#cb-open-btn').animate({
								opacity: '1' ,	 
								height: "toggle",
							}, 1000, function() {
						});
					});

					$("#cb-close-btn-responsive").click(function(){
						$('.cb-container-no-touch').animate({
								 height: [ "toggle", "swing" ],
							}, 500, function() {
						});
						$('#cb-close-btn').animate({
							opacity: '.5' ,	 
							height: "0px",
							}, 1000, function() {
							});
						$('#cb-open-btn').animate({
								opacity: '1' ,	 
								height: "toggle",
							}, 1000, function() {
						});
					});

					$('#cb-message').on('input', function () {
						if ($(this).text() !== '') {
							$('#cb-msg-submit-btn').css('display', 'flex');
						} else {
							$('#cb-msg-submit-btn').hide();
						}
					});

					$("#cb-send-message-btn").click(async function(){
						let message = appendMessage()
						await sendMessage(message);
						console.log(goods_information)
					});

					$("#cb-message").keypress(async function(e){
						if (e.keyCode == 13) {
							e.preventDefault();
							let message = appendMessage()
							await sendMessage(message);
						}
					})

					function autoScrollDown() {
						var messageBody = $('.cb-content')[0];
						messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
					}

					function appendMessage() {
						let message_raw = $('#cb-message').text();
						let message = process_prompt(message_raw)
						$('#cb-message').empty();
						let message_div = document.createElement('div');
						let message_panel = document.createElement('span');
						message_panel.innerHTML = message_raw;
						message_div.setAttribute('class', 'cb-message-div');
						message_panel.setAttribute('class', 'cb-client-message-span');
						let content = document.getElementsByClassName('cb-content')[0];
						
						message_div.appendChild(message_panel);
						content.appendChild(message_div);
						autoScrollDown();

						show_bot_is_typing();
						return message
					}

					function show_bot_is_typing() {
						var append_html = 
											'<div class="cb-typing">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + 
														'<span></span>' +
														'<span></span>' +
														'<span></span>' +
													'</span>' +
												'</div>' + 
											'</div>';
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function answer_got_ready() {
						$('.cb-typing').remove()
					}

					async function sendMessage(message) {
						let checked_excel_0 = await check_if_prompt_is_for_excel_0(message)
						console.log(checked_excel_0)
						let checked_excel_1 = await check_if_prompt_is_for_excel_1(message)
						console.log(checked_excel_1)
						let checked_excel_2 = await check_if_prompt_is_for_excel_2(message)
						console.log(checked_excel_2)
						
						let checked_greeting_set = await check_if_prompt_is_greeting_set(message);
						console.log(checked_greeting_set)
						
						let checked_greeting = await check_if_prompt_is_greeting(message);
						console.log(checked_greeting)
						let checked_for_category = await check_and_get_category_word(message);
						console.log(checked_for_category)
						let checked_recommendation = await check_if_prompt_is_for_recommendation(message);
						console.log(checked_recommendation.trim().substring(0, 3))
						let checked_greeting_answer = await check_if_prompt_is_an_answer_to_greeting(message)
						console.log(checked_greeting_answer)
						let checked_all = await check_if_prompt_is_for_product_list(message);
						let checked_price_of_product = await check_if_prompt_is_for_price_of_product(message);
						if (checked_excel_0.includes('Yes')) {
							let checked_security = await check_if_prompt_is_for_security(message);
						console.log(checked_security)
							let checked_shipping_cost = await check_if_prompt_is_for_shipping_cost(message);
						console.log(checked_shipping_cost)
							let checked_shipping_time = await check_if_prompt_is_for_shipping_time(message);
						console.log(checked_shipping_time)
							if (checked_security.includes('Yes')) {
								tell_about_security()
							} else if (checked_shipping_cost.includes('Yes')) {
								tell_about_shipping_cost()
							} else if (checked_shipping_time.includes('Yes')) {
								tell_about_shipping_time()
							}
						} else if (checked_excel_1.includes('Yes')) {
							let checked_about_us = await check_if_prompt_is_for_about_us(message);
						console.log(checked_about_us)
							let checked_afterpay = await check_if_prompt_is_for_afterpay(message);
						console.log(checked_afterpay)
							let checked_contact = await check_if_prompt_is_for_contact(message);
						console.log(checked_contact)
							let checked_debit_card = await check_if_prompt_is_for_debit_card(message);
						console.log(checked_debit_card)
							let checked_express_shipping = await check_if_prompt_is_for_express_shipping(message);
						console.log(checked_express_shipping)
							let checked_fee = await check_if_prompt_is_for_fee(message);
						console.log(checked_fee)
							let checked_find_product = await check_if_prompt_is_for_find_product(message);
						console.log(checked_find_product)
							let checked_international_shipping = await check_if_prompt_is_for_international_shipping(message);
						console.log(checked_international_shipping)
							if (checked_about_us.includes('Yes')) {
								tell_about_us()
							} else if (checked_afterpay.includes('Yes')) {
								tell_about_afterpay()
							} else if (checked_contact.includes('Yes')) {
								console.log('client is asking contact info')
								tell_about_contact()
							} else if (checked_debit_card.includes('Yes')) {
								// tell_about_debit_card()
							} else if (checked_express_shipping.includes('Yes')) {
								tell_about_express_shipping()
							} else if (checked_fee.includes('Yes')) {
								tell_about_fee()
							} else if (checked_find_product.includes('Yes')) {
								tell_about_find_product()
							} else if (checked_international_shipping.includes('Yes')) {
								tell_about_international_shipping()
							}
						} else if (checked_excel_2.includes('Yes')) {
							let checked_return = await check_if_prompt_is_for_return(message);
						console.log(checked_return)
							
							let checked_paypal_fee = await check_if_prompt_is_for_paypal_fee(message);
						console.log(checked_paypal_fee)
						// 	let checked_payment_method = await check_if_prompt_is_for_payment_method(message);
						// console.log(checked_payment_method)
							
						// 	let checked_shipping = await check_if_prompt_is_for_shipping(message);
						// console.log(checked_shipping)
						// 	let checked_tracking = await check_if_prompt_is_for_tracking(message);
						// console.log(checked_tracking)
						// 	let checked_order_status = await check_if_prompt_is_for_order_status(message);
						// console.log(checked_order_status)
							let checked_location = await check_if_prompt_is_for_location(message);
						console.log(checked_location)
							let checked_hours = await check_if_prompt_is_for_hours(message);
							console.log(checked_hours)

							if (checked_return.includes('Yes')) {
								tell_about_return()
							
							} else if (checked_paypal_fee.includes('Yes')) {
								tell_about_paypal_fees()
							// } else if (checked_payment_method.includes('Yes')) {
							// 	// tell_about_payment_method()
							
							// } else if (checked_shipping.includes('Yes')) {
							// 	// tell_about_shipping()
							// } else if (checked_tracking.includes('Yes')) {
							// 	// tell_about_tracking()
							// } else if (checked_order_status.includes('Yes')) {
							// 	// tell_about_order_status()
							} else if (checked_location.includes('Yes')) {
								tell_about_location()
							} else if (checked_hours.includes('Yes')) {
								tell_about_hours()
							}
						} else if (checked_greeting_set.includes('Yes')) {
							tell_about_greeting_set()
						} else if (checked_greeting.trim() == 'Yes') {
							console.log(message)
							greet_with_customer(message);
						} else if (checked_greeting_answer.trim() == 'Yes') {
							ask_what_needed()
						} else if (checked_for_category.includes('Yes')) {
							console.log('category filtering')
							let category = remove_special_characters(checked_for_category.split(',')[1].trim()).trim();
							let goods_names_string = get_goods_names_string()
							let filtered_by_category_string = await filter_goods_by_category(category, goods_names_string);
							console.log(filtered_by_category_string)
							let filtered_by_category = filtered_by_category_string.split(',')
							tell_how_many(category, filtered_by_category)
						} else if (checked_recommendation.includes('Yes')) {
							console.log('client wants to get a recommendation')
							recommendation_string = checked_recommendation.trim().substring(4, checked_recommendation.trim().length).trim()
							console.log(recommendation_string)
							recommendation_options = []
							if (recommendation_string.search(':') == -1) {
								recommendation_options = remove_special_characters(recommendation_string).split(' ')
								recommendation_options = recommendation_options.filter(item => item)
							} else {
								recommendation_options = remove_special_characters(recommendation_string.split(':')[1]).split(' ')
								recommendation_options = recommendation_options.filter(item => item)
							}
							recommend_goods_for_customer(recommendation_options, true);
						} else if (checked_all.trim() == 'Yes') {
							console.log('client want to have information of whole products in this site')
							inform_all_products();
						} else if (checked_price_of_product.includes('Yes')) {
							console.log('bot is to tell price to the client')
							tell_price(checked_price_of_product.split(',')[1].trim())
						} else {
							let checked_information = await check_if_prompt_is_for_information(message);
							console.log('checked_information', checked_information)
							let checked_price_desirable = await check_if_prompt_is_for_price(message);
							if (checked_information.includes('Yes')) {
								console.log('checking information')
								tell_product_information(remove_special_characters(checked_information.split(',')[1].trim()))
							} else if (checked_price_desirable.includes('Yes')) {
								pick_product_in_price_range(checked_price_desirable.split(',')[1].trim(), remove_special_characters(checked_price_desirable.split(',')[2].trim()))
							} else {
								let checked_for_object = await check_and_get_object_word(message);
								if (checked_for_object.includes('Yes')) {
									let noun = checked_for_object.split(',')[1].trim();
									topic_now = noun;
									let recommendation_options = remove_special_characters(noun).trim().split(' ')
									recommend_goods_for_customer(recommendation_options, false);
								} else {
									tell_this_is_sale_bot();
								}
							}
						}
					}

					async function check_if_prompt_is_for_excel_0(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is a question asked by a customer to online business. Say Yes if he wants to know anything about the security of business, Say Yes if he asks how much the shipping costs, and say yes if he asks if there is a minimum order amount to qualify for free shipping, and say yes if he wants more information about shipping fees or costs, Say Yes if he asks how long will the shipping time be, and say yes if he asks about how long the average shipping time would be, and say yes if he asks how long he should expect to wait for delivery, and say no if he asks about international shipping, and say no if he asks about express shipping, and say no if he asks about customer support or care or service, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_excel_1(prompt) {
						console.log(prompt)
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is a question asked by a customer to online business. Say Yes if he wants to know anything about the business, Say Yes if he wants to know about afterpay, Say Yes if he asks about customer service, Say Yes if he wants to know if he could use credit or debit card, say yes if he asks how to do if he does not have a debit card, and say yes if he asks if there is a minimum or maximum amount, and say yes if he asks if there is any extra charge when using a debit card, and say yes if he asks how long it takes for payment to processed when using a debit card, and say yes if he asks if he can get a refund if he paid with a debit card, Say Yes if he wants to know about express shipping, and say yes if he wants to know about standard shipping attributes, and say yes if he wants to know how fast he can receive his order or shipment, and say yes if he asks how much the shipping costs, and say yes if he asks about the delivery timeframe, and say yes if he asks about same-day shipping, Say Yes if he wants to know if he should pay additional fee or extra charge or surcharge or extra cost for using credit or debit card, and say yes if he asks if company add any fees for using cards, and say yes if he asks using a card comes with extra charges, Say Yes if he is asking help to find out some products, Say Yes if he wants to know about international shipping, and say yes if he asks if he can order the products if he is not in Australia, and say yes if he asks if he can purchase products from another country, and say yes if he asks how he can buy products if he lives outside of Australia, and say yes if he asks if there are additional charges for international orders, and say no if it is greeting, and say no if he asks a list of all products, and say No if he asks about a product or products or category, and say no if he asks if there is a product or he wants to be told about a product, and say no if he wants to be told more about a product, and say no if he would like to buy a product or products, and say no if he wants to know about a category, and say no if he would like to buy a product or products, Assume the last sentence is a question asked by a customer to online business. Say no if he wants to know if there is an physical store not only online one, and say no if he asks if there is a brick-and-mortar store, and say no if he asks where he can find the product for purchase, and say no if word "warehouse" or "office" is in the question, and Say no if he wants to know about payment method or how to act when his card is declined or the service can offer any discounts for using particular type of card or how he could know his card information is accurate or if there is a card that the service cannot accept, and say no if he asks about payment via bank transfer, and say no if he asks if there are hidden fees for payments, and say no if he asks how long it takes for payments to be processed, and say no if he asks if it is possible to pay with cryptocurrency, and say no if he asks if he can use multiple payment methods, and say no if he asks he can pay with a cashier check, and say no if he asks if there are fees for using Afterpay, and say no what currency the business accept for payment, and say no if he asks if there is a limit on the amount he can charge on his credit card, and say no if he is talking about Venmo account, and say no if he is asking if there are any specific credit cards that cannot be accepted, and say no if he can change credit card, and say no if he is asking if he can use gift card, and Say no if he wants to know about paypal or if he should pay surcharge, and Say no if he wants to know return or refund or exchange policy or how to act when he got what he did not want or if he could return an item, and Say no if he wants to know anything about the security of business, and Say no if he asks how much the shipping costs, and say no if he asks if there is a minimum order amount to qualify for free shipping, and say no if he wants more information about shipping fees or costs, and Say no if he asks how much the shipping time, and say yes if he asks about customer service information, but Say no if he asks about time of service or if he asks when he can contact the customer service and get the service, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_excel_2(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is a question asked by a customer to online business. Say Yes if he wants to know if there is an physical store not only online one, and say yes if he asks if there is a brick-and-mortar store, and say yes if he asks where he can find the product for purchase, and say yes if word "warehouse" or "office" is in the question, and Say Yes if he wants to know about payment method or how to act when his card is declined or the service can offer any discounts for using particular type of card or how he could know his card information is accurate or if there is a card that the service cannot accept, and say yes if he asks about payment via bank transfer, and say yes if he asks if there are hidden fees for payments, and say yes if he asks how long it takes for payments to be processed, and say yes if he asks if it is possible to pay with cryptocurrency, and say yes if he asks if he can use multiple payment methods, and say yes if he asks he can pay with a cashier check, and say yes if he asks if there are fees for using Afterpay, and say yes what currency the business accept for payment, and say yes if he asks if there is a limit on the amount he can charge on his credit card, and say yes if he is talking about Venmo account, and say yes if he is asking if there are any specific credit cards that cannot be accepted, and say yes if he can change credit card, and say yes if he is asking if he can use gift card, and Say Yes if he wants to know about paypal or if he should pay surcharge, and Say Yes if he wants to know return or refund or exchange policy or how to act when he got what he did not want or if he could return an item, and Say Yes if he wants to know anything about the security of business, and Say Yes if he asks how much the shipping costs, and say yes if he asks if there is a minimum order amount to qualify for free shipping, and say yes if he wants more information about shipping fees or costs, and Say Yes if he asks how much the shipping time, and Say Yes if he asks about time of service, and say yes if he asks when he can contact the customer service and get the service, and say no if it is greeting, and say no if he asks a list of all products, and say No if he asks about a product or products or category, and say no if he asks if there is a product or he wants to be told about a product, and say no if he wants to be told more about a product name, and say no if he would like to buy a product or products, and say no if he wants to know about a category, and say no if he asks to be told about a product, and say no if he asks to be told more about a product, and say no if he would like to buy a product or products, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_return(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he wants to know return or refund or exchange policy or how to act when he got what he did not want or if he could return an item, and say No if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_security(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is a question asked by a customer to online business. Say Yes if he wants to know anything about the security of business, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_afterpay(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he wants to know about afterpay, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_paypal_fee(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he wants to know about paypal or if he should pay surcharge, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_fee(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he wants to know if he should pay additional fee or extra charge or surcharge or extra cost for using credit or debit card, and say yes if he asks if company add any fees for using cards, and say yes if he asks using a card comes with extra charges and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_debit_card(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he wants to know if he could use credit or debit card, say yes if he asks how to do if he does not have a debit card, and say yes if he asks if there is a minimum or maximum amount, and say no if he asks if there is any extra charge when using a debit card, and say yes if he asks how long it takes for payment to processed when using a debit card, and say yes if he asks if he can get a refund if he paid with a debit card, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_payment_method(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he wants to know about payment method or how to act when his card is declined or the service can offer any discounts for using particular type of card or how he could know his card information is accurate or if there is a card that the service cannot accept, and say yes if he asks about payment via bank transfer, and say yes if he asks if there are hidden fees for payments, and say yes if he asks how long it takes for payments to be processed, and say yes if he asks if it is possible to pay with cryptocurrency, and say yes if he asks if he can use multiple payment methods, and say yes if he asks he can pay with a cashier check, and say yes if he asks if there are fees for using Afterpay, and say yes what currency the business accept for payment, and say yes if he asks if there is a limit on the amount he can charge on his credit card, and say yes if he is talking about Venmo account, and say yes if he is asking if there are any specific credit cards that cannot be accepted, and say yes if he can change credit card, and say yes if he is asking if he can use gift card, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_international_shipping(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is question asked by a customer to an Australian company. Say Yes if he wants to know about international shipping, and say yes if he asks if he can order the products if he is not in Australia, and say yes if he asks if he can purchase products from another country, and say yes if he asks how he can buy products if he lives outside of Australia, and say yes if he asks if there are additional charges for international orders, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_express_shipping(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is question asked by a customer. Say Yes if he wants to know about express shipping, and say yes if he wants to know about standard shipping attributes, and say yes if he wants to know how fast he can receive his order or shipment, and say yes if he asks how much the shipping costs, and say yes if he asks about the delivery timeframe, and say yes if he asks about same-day shipping, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_shipping_cost(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he asks how much the shipping costs, and say yes if he asks if there is a minimum order amount to qualify for free shipping, and say yes if he wants more information about shipping fees or costs, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_shipping_time(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he asks how much the shipping time, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_shipping(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes or No according to the following contains something that can be a product and if Yes pick the product name as singular form. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_find_product(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he is asking help to find out some products, and say no if it is greeting, and say no if he asks a list of all products, and say no if not. "' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_tracking(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes or No according to the following contains something that can be a product and if Yes pick the product name as singular form. ' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_order_status(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes or No according to the following contains something that can be a product and if Yes pick the product name as singular form. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_about_us(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is a question asked by a customer to online business. Say Yes if he wants to know anything about the business, and say no if he asks about international or express shipping, and say no if he asks about customer support or care or service, and say no if he asks about a product, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_location(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is a question asked by a customer to online business. Say Yes if he wants to know if there is an physical store not only online one, and say yes if he asks if there is a brick-and-mortar store, and say yes if he asks where he can find the product for purchase, and say yes if word "warehouse" or "office" is in the question, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_contact(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he asks about customer service, and say no if he asks about finding a product, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_hours(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if he asks about time of service. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_greeting_set(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. Say Yes if the following sentence starts with a greeting and if he needs an assitance or help or support or guidance or he has problem, and say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_for_price_of_product(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a customer. First of all say with "no" if the question has price value or price range value, but say with "yes" if he asks how much a product is or want to know the price of a product and if yes pick the object name after the word "yes, ". ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}
					
					async function check_and_get_object_word(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes or No according to the following contains something that can be a product and if Yes pick the product name as singular form. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_and_get_category_word(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say No if it has an individual product or Say Yes if the following contains some category, and if Yes pick the category name as singular form after comma. If the last sentence has a word "information" or "about" then say No. And if the last sentence has exact price, say No. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function filter_goods_by_category(category, goods_names_string) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Pick as much as possible among following products that belongs to ' + category + '. ' + goods_names_string ,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
						
					}

					async function check_if_prompt_is_greeting(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes if the following sentence is a greeting and say No if not. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_recommendation(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'say Yes if the last sentence is to get a recommendation and if yes, pick recommendation products in a word for the following sentence, say no if the question has price information, say no if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_an_answer_to_greeting(prompt) {
						console.log(prompt)
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes if the last sentence is an answer to greeting or part of it, or say No if not. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_product_list(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Assume the last sentence is said by a person and Say Yes if he wants to have or would like to be provided with a list of all products and say No if it has an individual product.  If it is not about all information say No. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_order_tracking(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes or No according to the following contains some order tracking and if Yes pick the product name as singular form. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_customer_service(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes or No according to the following contains some customer service and if Yes pick the customer service name as singular form. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_information(prompt) {
						console.log(prompt)
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Say Yes or No depending on the following is to find out some information or details of a product and if Yes pick the product name as singular form. And if the following sentence contains price, say No. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_desirable_price(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Answer with Yes or No according to the sentence following has price value if yes pick the price. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_price(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Answer with Yes or No according to the sentence following has price value if yes write the price, and if it has price range say yes and write the range, comma and the object of price. "' + prompt + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function sort_products(goods_names_string) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Make a group using following products according to the main category of usage of them. Every product within the same category should be in a new line. "' + goods_names_string + '"',
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function check_if_prompt_is_for_price_of_product(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": 'Last sentence contains a product name. Say Yes if following sentence asks price of it or is willing to know price of it, and if yes pick the product. If the last sentence has a word "information" or "about" then say No. And if the last sentence has nothing to do with price, say No. And if the last sentence is about desirable price, say No. ' + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function greet(prompt) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": "Greet according to the following. " + prompt,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										var ret = choice.text;
										resolve(ret);
									}
								}
							});
						});
					}

					async function get_synonyms(seed) {
						return new Promise((resolve, reject) => {
							$.ajax({
								type: "POST",
								url: "https://api.openai.com/v1/completions",
								headers: {
								"Content-Type": "application/json",
								"Authorization": "Bearer " + key
								},
								data: JSON.stringify({
								"model": "text-davinci-003",
								"prompt": "Say 40 words which are synonyms to the following word phrase seperating them by comma. " + seed,
								"max_tokens": 1024,
								"temperature": 0.5
								}),
								success: function(response) {
									var choices = response.choices;
									if( choices.length > 0) {
										var choice = choices[0];
										console.log('mossad3', choice.text)
										var temp = choice.text.split(',');
										let ret = temp.map((item) => {
											let proc = remove_special_characters(item).trim()
											console.log('fucking', proc)
											return proc
										})
										console.log('damn', ret)
										resolve(ret);
									}
								}
							});
						});
					}

					async function greet_with_customer(message) {
						var greeting = await greet(message)
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + greeting + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function ask_what_needed() {
						var message = 'Well, how can I help you?'
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_this_is_sale_bot() {
						var message = 'Sorry, this is a sale assistance bot. Not trained to answer question or have a conversation not related to sale.'
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_how_many(category, filtered_by_category) {
						// to upgrade
						// show button to show filtered_by_category array
						console.log('from tell how many: ', filtered_by_category)
						let temp_arr = filtered_by_category.map(item => remove_special_characters(item).trim())
						console.log('from tell how many: ', temp_arr)
						let product_panel_html_script = ''
						for (const item in temp_arr) {
							product_panel_html_script += ' -' + temp_arr[item] + '<br>'
						}

						var message = 'We have some ' + category + '. Currently we have ' + filtered_by_category.length + ' products.'
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message  + '<br>' + product_panel_html_script + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()	
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					async function inform_all_products() {
						let goods_names_string = get_goods_names_string()
						let sorted_products_string =await sort_products(goods_names_string)
						console.log('here is the names of products in this site.' + '\n', goods_names_string)
						console.log('here is the sorted result.' + '\n', sorted_products_string)
						process_sorted_products_string_and_tell(sorted_products_string)
						console.log(sorted_products_string.split('\n\n').shift())
					}

					function process_sorted_products_string_and_tell(sorted_products_string) {
						temp_arr = sorted_products_string.split('\n\n')
						temp_arr.shift()
						let message = 'We have '
						for (let i=0; i<temp_arr.length; i++) {
							let product_category = temp_arr[i].split(':')[0]
							let quantum = [...temp_arr[i].split(':')[1]].filter(l => l === '\n').length;
							message += quantum + (quantum == 1 ? ' kind' :' kinds') + ' of ' + product_category + ', '
						}
						message = message.substring(0, message.length - 1) + '.'
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					async function tell_product_information(product_name) {
						// console.log('client want product information for ', product_name_seed)
						// let synonyms_of_product_name = await get_synonyms(product_name_seed)
						// synonyms_of_product_name.unshift(product_name_seed)
						// console.log(synonyms_of_product_name)
						// for (const product_name in synonyms_of_product_name) {
							words_array_out_of_product_name = remove_special_characters(product_name).trim().split(' ')
							let goods_names = get_goods_names()
							let filtered_goods_names = []
							for (let i=0; i<goods_names.length; i++) {
								let contains_boolean = true
								for (let j=0; j<words_array_out_of_product_name.length; j++) {
									console.log('shit!!! : ', goods_names[i])

									contains_boolean = contains_boolean && remove_special_characters_and_whitespaces(goods_names[i].toLowerCase()).includes(words_array_out_of_product_name[j].toLowerCase())
								}
								if(contains_boolean) {
									filtered_goods_names.push(goods_names[i])
								}
							}
							console.log('shit!!! : ', filtered_goods_names)
							var information_of_products_text = ''
							for (const goods of goods_information) {
								let contains_boolean = true
								for (let j=0; j<words_array_out_of_product_name.length; j++) {
									contains_boolean = contains_boolean && remove_special_characters_and_whitespaces(goods[6].toLowerCase()).includes(words_array_out_of_product_name[j].toLowerCase())
								}
								if (contains_boolean) {
									information_of_products_text += goods[6].split(':')[1] + ' is: ' + 'price is ' + goods[5].split(':')[1] + ', ' + 'product version is ' + goods[4].split(':')[1] + ', ' + 'average rating is ' + goods[2].split(':')[1] + ', ' + 'review count is ' + goods[3].split(':')[1] + ', ' + (goods[1].split(':')[1] == 'instock' ? ' and is in stock. \n' : ' and out of stock. \n')
								}
							}
	
							var message = filtered_goods_names.length != 0 ? 'Currently we have ' + filtered_goods_names.length + ' kinds of ' + remove_special_characters(product_name) + '.\n' + information_of_products_text : 'Sorry, we do not have those product in our store at the moment. \nBut if you want to purchase it, we can guide you to other sites as following.\n'
							
							var append_html = filtered_goods_names.length != 0 ?
												'<div class="cb-div">' +
													'<div class="cb-bot-icon-div">' +
														'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
													'</div>' +
													'<div class="cb-message-div">' +
														'<span class="cb-bot-message-span">' + message + '</span>' +
													'</div>' + 
												'</div>'
												:
												'<div class="cb-div">' +
													'<div class="cb-bot-icon-div">' +
														'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
													'</div>' +
													'<div class="cb-message-div">' +
														'<div><span class="cb-bot-message-span">' + message + '</span></div>' +
														'<div class="cb-other-sties">' +
															'<div><a href="https://www.catch.com.au">https://www.catch.com.au</a></div>' +
															'<div><a href="https://www.kogan.com.au">https://www.kogan.com.au</a></div>' +
															'<div><a href="https://www.myer.com.au">https://www.myer.com.au</a></div>' +
															'<div><a href="https://www.klika.com.au">https://www.klika.com.au</a></div>' +
															'<div><a href="https://www.woolworths.com.au">https://www.woolworths.com.au</a></div>' +
															'<div><a href="https://www.bunnings.com.au">https://www.bunnings.com.au</a></div>' +
															'<div><a href="https://www.harveynorman.com.au">https://www.harveynorman.com.au</a></div>' +
															'<div><a href="https://www.jbhifi.com.au">https://www.jbhifi.com.au</a></div>' +
														'</div>' + 
													'</div>' + 
												'</div>'
							answer_got_ready()
	
							$('.cb-content').append(append_html);
							autoScrollDown();
						// }
					}

					function tell_price(product_name) {
						console.log(goods_information)
						words_array_out_of_product_name = remove_special_characters(product_name).trim().split(' ')
						let goods_names = get_goods_names()
						let filtered_goods_names = []
						for (let i=0; i<goods_names.length; i++) {
							let contains_boolean = true
							for (let j=0; j<words_array_out_of_product_name.length; j++) {
								contains_boolean = contains_boolean && remove_special_characters_and_whitespaces(goods_names[i].toLowerCase()).includes(words_array_out_of_product_name[j].toLowerCase())
							}
							if(contains_boolean) {
								filtered_goods_names.push(goods_names[i])
							}
						}
						var information_of_products_text = ''
						for (const goods of goods_information) {
							let contains_boolean = true
							for (let j=0; j<words_array_out_of_product_name.length; j++) {
								contains_boolean = contains_boolean && remove_special_characters_and_whitespaces(goods[6].toLowerCase()).includes(words_array_out_of_product_name[j].toLowerCase())
							}
							if (contains_boolean) {
								information_of_products_text += goods[6].split(':')[1] + ' is ' + goods[5].split(':')[1] + ' dollars. \n'
							}
						}

						var message = 'Currently we have ' + filtered_goods_names.length + ' kinds of ' + remove_special_characters(product_name) + '.\n' + information_of_products_text
						
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function pick_product_in_price_range(price, product_name) {
						console.log('pick_product_in_price_range')
						let price_array = price.replace(/[^0-9]/g, ' ').trim().split(' ')
						let price_around = price_array.length == 1 ? parseInt(price_array[0]) : 0
						let price_lower = price_array.length == 2 ? parseInt(price_array[0]) : 0
						let price_upper = price_array.length == 2 ? parseInt(price_array[1]) : 0
						console.log('price_around', price_around)
						console.log('price_lower', price_lower)
						console.log('price_upper', price_upper)
						console.log('product', product_name)

						words_array_out_of_product_name = remove_special_characters(product_name).trim().split(' ')

						var advice_according_to_text = ''
						for (const goods of goods_information) {
							let contains_boolean = true
							let price = parseInt(goods[5].split(':')[1])
							for (let j=0; j<words_array_out_of_product_name.length; j++) {
								contains_boolean = contains_boolean && remove_special_characters_and_whitespaces(goods[6].toLowerCase()).includes(words_array_out_of_product_name[j].toLowerCase()) && (price <= price_upper && price >= price_lower)
							}
							if (contains_boolean) {
								advice_according_to_text += goods[6].split(':')[1] + ' is ' + goods[5].split(':')[1] + ' dollars. \n'
							}
						}

						var information_of_products_text = ''
						for (const goods of goods_information) {
							let contains_boolean = true
							for (let j=0; j<words_array_out_of_product_name.length; j++) {
								contains_boolean = contains_boolean && remove_special_characters_and_whitespaces(goods[6].toLowerCase()).includes(words_array_out_of_product_name[j].toLowerCase())
							}
							if (contains_boolean) {
								information_of_products_text += goods[6].split(':')[1] + ' is ' + goods[5].split(':')[1] + ' dollars. \n'
							}
						}

						var message = advice_according_to_text != '' ? 'We have products that satisfy your requirement. ' + '\n' + advice_according_to_text : 'Sorry, we have no products that satisfy your requirement. ' + '\n' + information_of_products_text
						
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function recommend_goods_for_customer(recommendation_options, recommendation_flag) {
						let goods_names = get_goods_names()
						let filtered_goods_names = []
						let nouns = recommendation_options
						console.log('mossad1', nouns)

						
						console.log('mossad2', filtered_goods_names)
						if (recommendation_flag) {
							for (let i=0; i<goods_names.length; i++) {
								for (let j=0; j<nouns.length; j++) {
									if (remove_special_characters_and_whitespaces(goods_names[i].toLowerCase()).includes(nouns[j].toLowerCase()))
									filtered_goods_names.push(goods_names[i])
								}
							}
							var recommendation_options_string = 'Our recommendation options are '
							for (let i=0; i<nouns.length; i++) {
								if (i != nouns.length - 1) {
									recommendation_options_string += nouns[i] + ', '
								} else {
									recommendation_options_string = recommendation_options_string.substring(0, recommendation_options_string.length-2)
									recommendation_options_string += ' and ' + nouns[i] + '.'
								}
							}
							if (filtered_goods_names.length == 0) {
								var message = 'Sorry, we do not have those product in our store at the moment.'
								var append_html = 
													'<div class="cb-div">' +
														'<div class="cb-bot-icon-div">' +
															'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
														'</div>' +
														'<div class="cb-message-div">' +
															'<span class="cb-bot-message-span">' + recommendation_options_string + '<br>' + message + '</span>' +
														'</div>' + 
													'</div>';
								answer_got_ready()
								$('.cb-content').append(append_html);
								autoScrollDown();
							} else {
								console.log('filtered_goods_names.length here', filtered_goods_names.length)
								var message = 'Yeah, actually our store has those kinda products which you can check below.'
								var product_panel_html_script = ''
	
								for (let i=0; i<filtered_goods_names.length; i++) {
									product_panel_html_script += '- ' + filtered_goods_names[i] + '<br>'
								}
								var append_html = 
													'<div class="cb-div">' +
														'<div class="cb-bot-icon-div">' +
															'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
														'</div>' +
														'<div class="cb-message-div">' +
															'<span class="cb-bot-message-span">' + recommendation_options_string + '<br>' + message + '<br>' + product_panel_html_script + '</span>' +
														'</div>' + 
													'</div>';
								answer_got_ready()
								$('.cb-content').append(append_html);
								autoScrollDown();
							}
						} else {
							for (let i=0; i<goods_names.length; i++) {
								let contains_boolean = true
								for (let j=0; j<nouns.length; j++) {
									contains_boolean = contains_boolean && remove_special_characters_and_whitespaces(goods_names[i].toLowerCase()).includes(nouns[j].toLowerCase())
								}
								if(contains_boolean) {
									filtered_goods_names.push(goods_names[i])
								}
							}
							if (filtered_goods_names.length == 0) {
								var message = 'Sorry, we do not have those product in our store at the moment.'
								var append_html = 
													'<div class="cb-div">' +
														'<div class="cb-bot-icon-div">' +
															'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
														'</div>' +
														'<div class="cb-message-div">' +
															'<span class="cb-bot-message-span">' + message + '</span>' +
														'</div>' + 
													'</div>';
								answer_got_ready()
								$('.cb-content').append(append_html);
								autoScrollDown();
							} else {
								console.log('filtered_goods_names.length here', filtered_goods_names.length)
								var message = 'Yeah, actually our store has those kinda products which you can check below.'
								var product_panel_html_script = ''
	
								for (let i=0; i<filtered_goods_names.length; i++) {
									product_panel_html_script += '- ' + filtered_goods_names[i] + '<br>'
								}
								var append_html = 
													'<div class="cb-div">' +
														'<div class="cb-bot-icon-div">' +
															'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
														'</div>' +
														'<div class="cb-message-div">' +
															'<span class="cb-bot-message-span">' + message + '<br>' + product_panel_html_script + '</span>' +
														'</div>' + 
													'</div>';
								answer_got_ready()
								$('.cb-content').append(append_html);
								autoScrollDown();
							}
						}

					}

					function remove_special_characters(text) {
						return text.replace(/[^a-zA-Z0-9]/g, ' ')
					}

					function remove_special_characters_and_whitespaces(text) {
						return text.replace(/[^a-zA-Z0-9]/g, '')
					}

					function process_prompt(prompt) {
						if (prompt[prompt.length - 1] == '.' || prompt[prompt.length - 1] == '?' || prompt[prompt.length - 1] == '!') {
							return prompt.substring(0, prompt.length - 1)
						} else {
							return prompt
						}
					}

					function get_random_in_range(upper_limit) {
						 return Math.floor(Math.random() * upper_limit)
					}

					function tell_about_greeting_set() {
						var message = answer_greetings_set[get_random_in_range(answer_greetings_set.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_hours() {
						var message = answer_hours[get_random_in_range(answer_hours.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_contact() {
						var message = answer_contact[get_random_in_range(answer_contact.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_location() {
						var message = answer_location[get_random_in_range(answer_location.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_us() {
						var message = answer_about_us[get_random_in_range(answer_about_us.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_find_product() {
						var message = answer_find_product[get_random_in_range(answer_find_product.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_shipping_time() {
						var message = answer_shipping_time[get_random_in_range(answer_shipping_time.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_shipping_cost() {
						var message = answer_shipping_cost[get_random_in_range(answer_shipping_cost.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_international_shipping() {
						var message = answer_international_shipping[get_random_in_range(answer_international_shipping.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_express_shipping() {
						var message = answer_express_shipping[get_random_in_range(answer_express_shipping.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_payment_method() {
						var message = answer_payment_method[get_random_in_range(answer_payment_method.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_debit_card() {
						var message = answer_debit_card[get_random_in_range(answer_debit_card.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_fee() {
						var message = answer_fees[get_random_in_range(answer_fees.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_paypal_fees() {
						var message = answer_paypal_fees[get_random_in_range(answer_paypal_fees.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_afterpay() {
						var message = answer_afterpay[get_random_in_range(answer_afterpay.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_security() {
						var message = answer_security[get_random_in_range(answer_security.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

					function tell_about_return() {
						var message = answer_return[get_random_in_range(answer_return.length)]
						var append_html = 
											'<div class="cb-div">' +
												'<div class="cb-bot-icon-div">' +
													'<img src=<?php echo plugin_dir_url( __FILE__ ) . "img/bot.svg"; ?>>' +
												'</div>' +
												'<div class="cb-message-div">' +
													'<span class="cb-bot-message-span">' + message + '</span>' +
												'</div>' + 
											'</div>';
						answer_got_ready()
						$('.cb-content').append(append_html);
						autoScrollDown();
					}

				});

			</script>

			<div class='cb-container-no-touch'>
				<div class='cb-header'>
					<div class='cb-mark'><span class='cb-mark-title'>Z</span></div>
					<div class='cb-customer-contact'>
						<span class='cb-zakka-title'>Zakka Customer Service</span>
						<span class='cb-zakka-contact'>13152854298 | sales@zakka.com.au</span>
					</div>
					<div id='cb-close-btn-responsive' class="cb-close-img-responsive"></div>
				</div>
				<div class='cb-content'></div>
				<div class='cb-footer'>
					<!-- <div>
						<textarea id='cb-message' placeholder='Type a message here...'></textarea>
						<a>
							<svg width="18" height="18" viewBox="0 0 535.5 535.5"><polygon id='cb-send-message-btn' points="0,497.25 535.5,267.75 0,38.25 0,216.75 382.5,267.75 0,318.75" fill="#33383ab3"></polygon></svg>
						</a>
					</div> -->
					<div class="cb-msg-div">
						<span id='cb-message' placeholder='Type a message here...' contenteditable></span>
						<a class="submit-btn-css" id="cb-msg-submit-btn">
							<svg width="18" height="18" viewBox="0 0 535.5 535.5"><polygon id='cb-send-message-btn' points="0,497.25 535.5,267.75 0,38.25 0,216.75 382.5,267.75 0,318.75" fill="#33383ab3"></polygon></svg>
						</a>
					</div>
				</div>
			</div>
			<div class="cb-chat-btn-toggle">
				<div id='cb-open-btn' class="cb-open-img"></div>
				<div id='cb-close-btn' class="cb-close-img"></div>
			</div>
<?php
	}

	add_action('wp_footer','chatbot_plugin');
?>
