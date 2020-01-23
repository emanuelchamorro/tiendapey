{*
    * DISCLAIMER
    *
    * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
    * versions in the future. If you wish to customize PrestaShop for your
    * needs please refer tohttp://www.prestashop.com for more information.
    * We offer the best and most useful modules PrestaShop and modifications for your online store.
    *
    * @category  PrestaShop Module
    * @author    knowband.com <support@knowband.com>
    * @copyright 2017 Knowband
    * @license   see file: LICENSE.txt
    *
    * Description
    *
    * Admin tpl file
    *}
    
{literal}
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="width=device-width" initial-scale="1.0" user-scalable="yes"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="format-detection" content="date=no"/>
<meta name="format-detection" content="address=no"/>
<meta name="format-detection" content="email=no"/>
<meta name="robots" content="noindex,nofollow"/>
<style type="text/css">.ReadMsgBody{ width: 100%; }
            .ExternalClass{ width: 100%; }
            .ExternalClass *{ line-height: 100%; }
            .ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
                line-height:100%;
            }
            body{
                margin: 0;
                padding: 0;
            }
            body,table,td,p,a,li{
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }
            table td{ border-collapse: collapse; }
            table{
                border-spacing: 0;
                border-collapse: collapse;
            }
            p,a,li,td,blockquote{
                mso-line-height-rule: exactly;
            }
            p,a,li,td,body,table,blockquote{
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }
            img,a img{
                border: 0;
                outline: none;
                text-decoration: none;
            }
            img{
                -ms-interpolation-mode: bicubic;
            }
            a[href^=tel],a[href^=sms]{
                color: inherit;
                cursor: default;
                text-decoration: none;
            }
            a[x-apple-data-detectors]{
                color: inherit!important;
                text-decoration: none!important;
                font-size: inherit!important;
                font-family: inherit!important;
                font-weight: inherit!important;
                line-height: inherit!important;
            }
            .mlEmailContainer{
                max-width: 640px!important;
            }
            @media only screen and (min-width:768px){
                .mlEmailContainer{
                    width: 640px!important;
                }
            }
            @media only screen and (max-width: 640px) {
                .mlTemplateContainer{
                    padding: 10px 10px 0 10px;
                }
                .mlContentTable{
                    width: 100%!important;
                    min-width: 200px!important;
                }
                /* -- */
                .mlContentBlock{
                    float: none!important;
                    width: 100%!important;
                }
                /* -- */
                .mlContentOuter{
                    padding-bottom: 0px!important;
                    padding-left: 25px!important;
                    padding-right: 25px!important;
                    padding-top: 25px!important;
                }
                /* -- */
                .mlBottomContentOuter{
                    padding-bottom: 25px !important;
                }
                .mlLeftRightContentOuter {
                    padding-left: 5px!important;
                    padding-right: 5px!important;
                }
                .mlContentContainerOuter{
                    padding-left: 0px!important;
                    padding-right: 0px!important;
                }
                /* -- */
                .mlContentContainer{
                    padding-left: 25px!important;
                    padding-right: 25px!important;
                }
                /* -- */
                .mlContentButton a,
                .mlContentButton span{
                    width: auto!important;
                }
                /* -- */
                .mlContentImage img,
                .mlContentRSS img{
                    height: auto!important;
                    width: 100%!important;
                }
                .mlContentImage{
                    height: 100%!important;
                    width: auto!important;
                }
                .mlContentLogo img{
                    height: auto!important;
                    width: 100%!important;
                }
                /* -- */
                .mlContentContainer h1{
                    font-size: 24px!important;
                    line-height:125%!important;
                    margin-bottom: 0px!important;
                }
                /* -- */
                .mlContentContainer h2{
                    font-size: 18px!important;
                    line-height:125%!important;
                    margin-bottom: 0px!important;
                }
                /* -- */
                .mlContentContainer h3{
                    font-size: 16px!important;
                    line-height:125%!important;
                    margin-bottom: 0px!important;
                }
                /* -- */
                .mlContentContainer p,
                .mlContentContainer .mlContentRSS{
                    font-size: 16px!important;
                    line-height:150%!important;
                }
                /* -- */
                .mobileHide{
                    display: none!important;
                }
                /* -- */
                .alignCenter{
                    height: auto!important;
                    text-align: center!important
                }
                /* -- */
                .marginBottom{
                    margin-bottom: 25px!important;
                }
                /* -- */
                .mlContentHeight{
                    height: auto!important;
                }
                .mlContentGap{
                    height: 25px!important;
                }
                .mlDisplayInline {
                    display: inline-block!important;
                    float: none!important;
                }
                body{
                    margin: 0px!important;
                    padding: 0px!important;
                }
                body,table,td,p,a,li,blockquote{
                    -webkit-text-size-adjust: none!important;
                }
            }
</style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" dir="ltr" style="padding: 0; margin: 0; -webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; background: #ECF0F1;" data-gr-c-s-loaded="true">

<table background="{minimal_img_path}"bgcolor="#ECF0F1" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%" width="100%">
	<tbody>
		<tr>
			<td align="center" class="mlTemplateContainer">
			<table align="center" border="0" cellpadding="0" cellspacing="0" class="mlEmailContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%" width="100%">
				<tbody>
					<tr>
						<td align="center"><!--<![endif]--><!-- Content starts here -->
						<table align="center" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" width="640">
							<tbody>
								<tr>
									<td height="30">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<table align="center" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td class="mlContentTable">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#00d0c0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55149655" style="background: #00d0c0; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#00d0c0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #00d0c0; width: 640px;" width="640">
										<tbody>
											<tr>
												<td align="left" class="mlContentContainer" style="padding: 0px 50px 0px 50px; font-family: Helvetica; font-size: 14px; color: #7F8C8D; line-height: 23px;">
												<p style="margin: 0px 0px 10px 0px; line-height: 23px;text-align: center;">&nbsp;</p>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#00d0c0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55149967" style="background: #00d0c0; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#00d0c0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #00d0c0; width: 640px;" width="640">
										<tbody>
											<tr>
                                                                                            <td align="center" class="mlContentContainer mlContentImage mlContentHeight" style="padding: 0px 50px 10px 50px;"><h1>{shop_name}</h1></td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#00d0c0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55150241" style="background: #00d0c0; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#00d0c0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #00d0c0; width: 640px;" width="640">
										<tbody>
											<tr>
												<td align="center" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 15px; color: #ffffff; line-height: 25px;">
												<p style="margin: 0px 0px 10px 0px;        line-height: 25px;text-align: center;"><h2>New Review Posted on {product_name}!</h2></p>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55150427" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #FFFFFF; width: 640px;" width="640">
										<tbody>
											<tr>
												<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7F8C8D; line-height: 23px;">
												<h2 style="line-height: 26px; text-decoration: none; font-weight: bold; margin: 0px 0px 10px 0px;font-family: Helvetica; font-weight: bold; font-size: 20px; color: #000000; text-align: left;">Hi,</h2>

												<p style="margin: 0px 0px 10px 0px; line-height: 23px;">Congratulations !! {customer_name} posted a new review on your shop. Review is described below:</p>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55150501" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #FFFFFF; width: 640px;" width="640">
										<tbody>
											<tr>
												<td class="mlContentContainer" style="padding: 15px 50px 0px 50px;">
												<table border="0" cellpadding="0" cellspacing="0" style="border-top: 1px solid #d8d8d8;" width="100%">
													<tbody>
														<tr>
															<td height="15px" width="100%">&nbsp;</td>
														</tr>
													</tbody>
												</table>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55148241" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #FFFFFF; width: 640px;" width="640">
										<tbody>
											<tr>
												<td align="left" class="mlContentContainer" style="padding: 0px 50px 0px 50px; font-family: Helvetica; font-size: 14px; color: #7F8C8D; line-height: 23px;">
												<p style="margin: 0px 0px 10px 0px; line-height: 23px;text-align: center;"><strong>Review Description:</strong></p>

												<p style="margin: 0px 0px 20px 0px; line-height: 23px;text-align: center; font-size: 20px;color:#000;">{review_content}</p>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55148245" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #FFFFFF; width: 640px;" width="640">
										<tbody>
											<tr>
												<td class="mlContentContainer" style="padding: 0px 50px 0px 50px;">
												<table border="0" cellpadding="0" cellspacing="0" style="border-top: 1px solid #d8d8d8;" width="100%">
													<tbody>
														<tr>
															<td height="11px" width="100%">&nbsp;</td>
														</tr>
													</tbody>
												</table>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55150703" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #FFFFFF; width: 640px;" width="640">
										<tbody>
											<tr>
												<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7F8C8D; line-height: 23px;">
												<p style="margin: 0px 0px 10px 0px; line-height: 23px;"></p>

												<p style="margin: 0px 0px 10px 0px; line-height: 23px;">Best regards<br />
												<br />
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#00D0C0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55148251" style="background: #00D0C0; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#00D0C0" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #00D0C0; width: 640px;" width="640">
										<tbody>
											<tr>
												<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7F8C8D; line-height: 23px;">
												<p style="margin: 0px 0px 10px 0px;line-height: 23px;text-align: center;"><span style="font-size: 20px;"><a href="#" style="color: #ffffff; text-decoration: none;"></a></span></p>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55148239" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55153137" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #FFFFFF; width: 640px;" width="640">
										<tbody>
											<tr>
												<td>
												<div class="html-content" style="text-align:center;">
												<ul style="display:inline-block;text-align:center;list-style-type:none">
													<li style="display:inline-block;"><a href="#"><img src="{fb_img_path}" /> </a></li>
													<li style="display:inline-block;"><a href="#"><img src="{tumbler_img_path}" /> </a></li>
													<li style="display:inline-block;"><a href="#"><img src="{pininterest_img_path}" /> </a></li>
													<li style="display:inline-block;"><a href="#"><img src="{twitter_img_path}" /> </a></li>
												</ul>
												</div>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" id="ml-block-55152047" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td>
									<table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="background: #FFFFFF; width: 640px;" width="640">
										<tbody>
											<tr>
												<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7F8C8D; line-height: 23px;">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table align="center" border="0" cellpadding="0" cellspacing="0" class="mlContentTable" style="min-width: 640px; width: 640px;" width="640">
							<tbody>
								<tr>
									<td height="30">&nbsp;</td>
								</tr>
							</tbody>
						</table>
						<!-- Content ends here --><!--[if !mso]><!-- --></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</body>
{/literal}
<!--<![endif]-->