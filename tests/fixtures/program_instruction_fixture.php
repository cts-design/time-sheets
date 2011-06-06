<?php
/* ProgramInstruction Fixture generated on: 2011-06-06 14:37:27 : 1307385447 */
class ProgramInstructionFixture extends CakeTestFixture {
	var $name = 'ProgramInstruction';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'text' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'program_id' => array('column' => 'program_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'text' => '<br>In order for your child to qualify for VPK, you must be a resident of the \r\nState of Florida and your child must be 4 years old on or before September \r\n1<sup>st</sup> of the program year you are applying for.<br><br><span style=\"font-weight: bold;\">Required Documents:</span><br><p class=\"MsoNormal\">Driver License or Photo ID<br>Proof of \r\nResidency; only if address is not correct on Driver\'s License or Photo ID Proof \r\nof your child\'s age<br><br><strong>Examples of proof of residency:</strong>&nbsp; \r\n<br>Valid Florida Driver\'s License with current address<br>Current Utility bill \r\nin your name<br>Current Pay Stub</p>\r\n<p class=\"MsoNormal\"><strong>Examples of proof of age:<br></strong>Birth \r\nCertificate<br>Immunization Record signed by a doctor or public health \r\noffice<br>Passport</p>\r\n<p class=\"MsoNormal\">&nbsp;&nbsp;</p>\r\n<p class=\"MsoNormal\">If you prefer to provide the documents by a method other than \r\nupload, you have the following options:</p>\r\n<p class=\"MsoNormal\">Mail the documents to:<br>Coordinated Child Care VPK \r\nOnline<br>6500 102<sup>nd</sup> Avenue North<br>Pinellas Park, FL&nbsp; 33782</p>\r\n<p class=\"MsoNormal\">Drop the documents off at any CCC office listed on the CCC \r\nwebsite under locations. </p>\r\n<p class=\"MsoNormal\">Fax the documents to (727) 547-2993.</p>\r\n<p class=\"MsoNormal\"><b>Please be advised that the VPK Application is not \r\nconsidered complete without the Proof of Residency and Date of Birth \r\ndocuments.</b></p>',
			'type' => 'main',
			'created' => '0000-00-00 00:00:00',
			'modified' => '2011-05-26 16:16:29'
		),
		array(
			'id' => 2,
			'program_id' => 1,
			'text' => '<P><FONT size=2><FONT color=#008000><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; COLOR: #333333; FONT-SIZE: 10pt\"><STRONG>Application Progress:</STRONG></SPAN><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; COLOR: #333333; FONT-SIZE: 10pt\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<STRONG>&nbsp;</STRONG><FONT color=#339966><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; COLOR: #333333; FONT-SIZE: 10pt\">&nbsp;&nbsp;&nbsp;&nbsp;</SPAN><FONT color=#008000><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; FONT-SIZE: 10pt\"><STRONG> 1. Register </STRONG></SPAN></FONT><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; COLOR: #333333; FONT-SIZE: 10pt\">|<STRONG> </STRONG></SPAN><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; FONT-SIZE: 10pt\"><STRONG><FONT color=#008000>2. Orientation</FONT></STRONG></SPAN><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; COLOR: #c0c0c0; FONT-SIZE: 10pt\"> | </SPAN><FONT color=#c0c0c0><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; FONT-SIZE: 10pt\">3. VPK Application</SPAN></FONT><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; COLOR: #c0c0c0; FONT-SIZE: 10pt\"> | </SPAN><FONT color=#c0c0c0><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; FONT-SIZE: 10pt\">4. Submit Documents</SPAN></FONT><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; COLOR: #c0c0c0; FONT-SIZE: 10pt\"> | 5. </SPAN><FONT color=#c0c0c0><SPAN style=\"FONT-FAMILY: Tahoma,sans-serif; FONT-SIZE: 10pt\">Download and Print COE<BR></SPAN></FONT></FONT></SPAN></FONT></FONT><BR>Below you will view a presentation of the rules and regulations of the VPK Program.&nbsp; <BR>Please view this information thoroughly to help ensure the success of your child�s VPK experience.&nbsp; After the presentation, you will be asked to acknowledge your complete understanding of the information presented.&nbsp;<BR><BR><STRONG>Please pay close attention as you view the presentation on the Voluntary Pre-Kindergarten (VPK) Program Rules and Regulations. <BR><BR></STRONG>You must view the orientation in its entirety. Once viewed please check the box to continue the online process.&nbsp; If you do not completely understand the information, you have the following options at this time:<BR><BR>�&nbsp;View the presentation online again to gain a clear understanding<BR>�&nbsp;Call&nbsp; the VPK Online team @ 727-547-5700 for receive further assistance&nbsp; <BR>�&nbsp;Come to a CCC office to complete the process in person<BR><BR>If you would like to view the full version of this orientation, please <STRONG><U>CLICK HERE.<BR></U></STRONG></P>',
			'type' => 'media',
			'created' => '0000-00-00 00:00:00',
			'modified' => '2011-05-25 12:50:00'
		),
		array(
			'id' => 3,
			'program_id' => 1,
			'text' => '<P><FONT color=#339966 size=3><STRONG>Completed --&gt; Your Registration account has been created.<BR>Completed --&gt; Review and acknowledge the VPK orientation.<BR></STRONG></FONT><BR>You still need to complete the following<BR><BR>•&nbsp;Fill out and submit your child\\\'s VPK application (current page)<BR>•&nbsp;Upload or submit required documentation<BR>•&nbsp;Download and print the Certificate of Eligibility (COE) form<BR><BR><STRONG>Please complete the Application Form in its entirety, and sign indicating your agreement with the certification at the end of the page. <BR></STRONG>If you do not completely understand the information, you have the following options at this time:<BR><BR>•&nbsp;Call&nbsp; the VPK Online team @ 727-547-5700 for receive further assistance&nbsp; <BR>•&nbsp;Come to a CCC office to complete the process in person<BR></P>',
			'type' => 'form',
			'created' => '0000-00-00 00:00:00',
			'modified' => '2011-05-25 12:57:27'
		),
		array(
			'id' => 4,
			'program_id' => 1,
			'text' => '<P><BR><STRONG>In order for your child to qualify for VPK, you must be a resident of the State of Florida and your child must be 4 years old on or before September 1st of the program year you are applying for.&nbsp; <BR><BR>Required Documents:<BR></STRONG>Driver License or Photo ID<BR>Proof of Residency; only if address is not correct on Driver’s License or Photo ID <BR>Proof of your child’s age<BR><BR><STRONG>Examples of proof of residency:</STRONG>&nbsp; <BR>•&nbsp;Valid Florida Driver’s License with current address<BR>•&nbsp;Current Utility bill in your name<BR>•&nbsp;Current Pay Stub<BR><BR><STRONG>Examples of proof of age:<BR></STRONG>•&nbsp;Birth Certificate<BR>•&nbsp;Immunization Record signed by a doctor or public health office<BR>•&nbsp;Passport<BR><BR><STRONG>Please be advised that the VPK Application is not considered complete without the Proof of Residency and Date of Birth documents.<BR><BR>Please attach the required files below.<BR><BR></STRONG><BR></P>',
			'type' => 'document',
			'created' => '0000-00-00 00:00:00',
			'modified' => '2011-05-31 13:24:48'
		),
		array(
			'id' => 5,
			'program_id' => 1,
			'text' => '<P style=\\\"LINE-HEIGHT: normal; MARGIN: 0in 0in 0pt; mso-layout-grid-align: none\\\" class=MsoNormal><B><SPAN style=\\\"FONT-FAMILY: \\\'Arial\\\',\\\'sans-serif\\\'; FONT-SIZE: 9pt\\\">I have examined this application and, to the best of my knowledge and belief, the information provided is true and correct. If I enroll my child in the VPK program, I understand that my child will be required to participate in the statewide kindergarten screening to determine readiness for kindergarten. I understand that transportation for the program is my (<I>parent’s or guardian’s</I>) responsibility. I also understand that it is my responsibility to locate an eligible VPK provider or school and enroll my child with the provider or school. I understand that I may enroll my child in either a school-year program (<I>540 instructional hours</I>) or a summer program (<I>300 instructional hours</I>).<BR></SPAN></B><B><SPAN style=\\\"FONT-FAMILY: \\\'Arial\\\',\\\'sans-serif\\\'; FONT-SIZE: 9pt\\\"><BR>I further understand that I (<I>parent or guardian</I>) must follow the provider’s or school’s attendance policy and verify my child’s attendance each month.<BR><BR></SPAN></B></P>',
			'type' => 'esign',
			'created' => '0000-00-00 00:00:00',
			'modified' => '2011-05-25 12:53:00'
		),
		array(
			'id' => 6,
			'program_id' => 1,
			'text' => '<P><BR><STRONG>Thank you for uploading your documents. <BR></STRONG><BR>Your application and documents will be reviewed within 48-72 hours. You will receive an email when the process is complete, or if there are any issues that need attention. <BR><BR>If you have any questions you may contact the VPK admin office at....<BR><BR>Thank You!</P>',
			'type' => 'uploaded_docs',
			'created' => '2011-05-27 14:40:07',
			'modified' => '2011-05-31 13:19:04'
		),
		array(
			'id' => 7,
			'program_id' => 1,
			'text' => 'You have 3 chooses to submit your documents.<BR><BR><BR>1) Drop the documents off at any CCC office listed on the CCC website under <A href=\"https://atlas.local/%22http://childcarepinellas.org//%22\">locations.</A> <BR><BR>2) Fax the documents to (727) 547-2993.<BR><BR>3) Mail the documents to:<BR>Coordinated Child Care VPK Online<BR>6500 102nd Avenue North<BR>Pinellas Park, FL&nbsp; 33782<BR><BR><STRONG><BR>Please be advised that the VPK Application is not considered complete without the Proof of Residency and Date of Birth documents.<BR></STRONG><BR><BR>',
			'type' => 'dropping_off_docs',
			'created' => '2011-05-27 14:40:51',
			'modified' => '2011-05-31 13:24:17'
		),
	);
}
