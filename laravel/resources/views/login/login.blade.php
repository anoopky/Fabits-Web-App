@extends('template.master')
@section('head')
    <title>fabits.in | Login Policy</title>
@endsection
@section('content')
    <style>
        body {
            background: url("{{  Cloudder::show('fabits/background_image1', array()) }}") no-repeat fixed;
            background-size: cover;
        }
    </style>
    <!--#2196F3 !important blue-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12  pt-1 text-white lead text-xs-center" style="height: 50px;">
                <a href="/" data-loc="page-full"><img src="/img/fabits.png" alt="fabits.in" style="width: 180px;"></a>

            </div>
        </div>
        @include('template.notification')
        <div class="row mt-8per ">
            <div class="offset-md-1 col-xs-12 col-md-10 b-white">
                <h1 class="display-4 text-xs-center p-2"> Login Policy</h1>
                <P class="lead text-black">


                    “You” refers to the user of this website and its related services, and as such You have gained the right to use this website by respecting the websitelicable Terms of Use described in detail below. Fabits the exclusive owner and operator of Fabits.in . As used in this Terms of Use Agreement, "we" refers to Fabits, subsidiary, division or assign of Fabits. “Service(s)” refers to Your use of the website for  purpose it is released.
                    <br>
                    This Terms of Use Agreement (the "Agreement") sets forth the terms and conditions that websitely to Your use of the website and all services offered by Fabits. By completing the signup process You are indicating that You agree to be bound by all of the terms in this Agreement. Please print and keep a copy of this Agreement for Your records.
                    YOU ARE SOLELY RESPONSIBLE FOR YOUR INTERACTIONS WITH OTHER MEMBERS. YOU UNDERSTAND THAT Fabits CURRENTLY DOES NOT CONDUCT CRIMINAL BACKGROUND CHECKS ON ITS MEMBERS. Fabits ALSO DOES NOT INQUIRE INTO THE BACKGROUNDS OF ALL OF ITS MEMBERS OR ATTEMPT TO VERIFY THE STATEMENTS OF ITS MEMBERS. Fabits MAKES NO REPRESENTATIONS OR WARRANTIES AS TO THE CONDUCT OF MEMBERS OR THEIR COMPATIBILITY WITH ANY CURRENT OR FUTURE MEMBERS. Fabits RESERVES THE RIGHT TO CONDUCT ANY CRIMINAL BACKGROUND CHECK OR OTHER SCREENINGS (SUCH AS SEX OFFENDER REGISTER SEARCHES), AT ANY TIME AND USING AVAILABLE PUBLIC RECORDS. IN NO EVENT SHALL Fabits BE LIABLE FOR ANY DAMAGES WHATSOEVER, WHETHER DIRECT, INDIRECT, GENERAL, SPECIAL, COMPENSATORY, CONSEQUENTIAL, AND/OR INCIDENTAL, ARISING OUT OF OR RELATING TO THE CONDUCT OF YOU OR ANYONE ELSE IN CONNECTION WITH THE USE OF THE SERVICE, INCLUDING WITHOUT LIMITATION, BODILY INJURY, EMOTIONAL DISTRESS, AND/OR ANY OTHER DAMAGES RESULTING FROM COMMUNICATIONS OR MEETINGS WITH OTHER REGISTERED USERS OF THIS SERVICE OR PERSONS YOU MEET THROUGH THIS SERVICE. YOU AGREE TO TAKE REASONABLE PRECAUTIONS IN ALL INTERACTIONS WITH OTHER MEMBERS OF THE SERVICE, PARTICULARLY IF YOU DECIDE TO MEET OFFLINE OR IN PERSON. YOU UNDERSTAND THAT Fabits MAKES NO GUARANTEES, EITHER EXPRESS OR IMPLIED, REGARDING YOUR ULTIMATE COMPATIBILITY WITH INDIVIDUALS YOU MEET THROUGH THE SERVICE. YOU SHOULD NOT PROVIDE YOUR FINANCIAL INFORMATION (FOR EXAMPLE, YOUR CREDIT CARD OR BANK ACCOUNT INFORMATION) TO OTHER MEMBERS.
                    <br>
                    From time to time we will use some elements of Your personal information (such as Your personal preferences or Your profile information) to generate and send You a list of possible matches from among our other users.
                    <br>
                    We operate an electronic customer support centre to accept and address Your questions, concerns, or complaints. When You contact our customer support centre, we may ask for personal information to help us respond to Your inquiry or to verify Your identity. For example if You need to make a change to Your account, we will ask for personal information to verify that You are the account holder. We may also monitor or record Your online or telephone discussions with our customer support representatives for training purposes and to ensure service quality.
                    <br>
                    In some cases, we automatically collect certain information.
                    <br>
                    When using our website, we may collect the Internet Protocol (IP) address of Your computer, the IP address of Your Internet Service Provider, the date and time You access our website, the Internet address of the web site from which You linked directly to our website, the operating system You are using, the Internet browser You are using, the sections of the website You visit, the website pages read and images viewed, and the content You download from the website. This information is used for website and system administration purposes and to improve the website.
                    <br>
                    The website uses "cookies", a technology that installs a small amount of information on a website user's computer to permit the website to recognize future visits using that computer. Cookies enhance the convenience and use of the website. For example, the information provided through cookies is used to recognize You as a previous user of the website, to offer personalized Web page content and information for Your use, and to otherwise facilitate Your website experience. You may choose to decline cookies if Your browser permits, but doing so may affect Your use of the website and Your ability to access certain features of the website or engage in transactions through the website.
                    YOU ARE SOLELY RESPONSIBLE FOR YOUR INTERACTIONS WITH OTHER MEMBERS. YOU UNDERSTAND THAT Fabits CURRENTLY DOES NOT CONDUCT CRIMINAL BACKGROUND CHECKS ON ITS MEMBERS. Fabits ALSO DOES NOT INQUIRE INTO THE BACKGROUNDS OF ALL OF ITS MEMBERS OR ATTEMPT TO VERIFY THE STATEMENTS OF ITS MEMBERS. Fabits MAKES NO REPRESENTATIONS OR WARRANTIES AS TO THE CONDUCT OF MEMBERS OR THEIR COMPATIBILITY WITH ANY CURRENT OR FUTURE MEMBERS. Fabits RESERVES THE RIGHT TO CONDUCT ANY CRIMINAL BACKGROUND CHECK OR OTHER SCREENINGS (SUCH AS SEX OFFENDER REGISTER SEARCHES), AT ANY TIME AND USING AVAILABLE PUBLIC RECORDS. IN NO EVENT SHALL Fabits BE LIABLE FOR ANY DAMAGES WHATSOEVER, WHETHER DIRECT, INDIRECT, GENERAL, SPECIAL, COMPENSATORY, CONSEQUENTIAL, AND/OR INCIDENTAL, ARISING OUT OF OR RELATING TO THE CONDUCT OF YOU OR ANYONE ELSE IN CONNECTION WITH THE USE OF THE SERVICE, INCLUDING WITHOUT LIMITATION, BODILY INJURY, EMOTIONAL DISTRESS, AND/OR ANY OTHER DAMAGES RESULTING FROM COMMUNICATIONS OR MEETINGS WITH OTHER REGISTERED USERS OF THIS SERVICE OR PERSONS YOU MEET THROUGH THIS SERVICE. YOU AGREE TO TAKE REASONABLE PRECAUTIONS IN ALL INTERACTIONS WITH OTHER MEMBERS OF THE SERVICE, PARTICULARLY IF YOU DECIDE TO MEET OFFLINE OR IN PERSON. YOU UNDERSTAND THAT Fabits MAKES NO GUARANTEES, EITHER EXPRESS OR IMPLIED, REGARDING YOUR ULTIMATE COMPATIBILITY WITH INDIVIDUALS YOU MEET THROUGH THE SERVICE. YOU SHOULD NOT PROVIDE YOUR FINANCIAL INFORMATION (FOR EXAMPLE, YOUR CREDIT CARD OR BANK ACCOUNT INFORMATION) TO OTHER MEMBERS.
                    <br>
                    You will not forward chain letters through the Service.
                    <br>
                    You will not use the Service to infringe the privacy rights, property rights, or any other rights of any person. You will not post messages, pictures or recordings or use the Service in any way that; violates, plagiarizes or infringes upon the rights of any third party, including but not limited to any copyright or trade-mark law, privacy or other personal or proprietary rights, or is fraudulent or otherwise unlawful or violates any law.
                    <br>
                    You will not use the Service to distribute, promote or otherwise publish any material containing any solicitation for funds, advertising or solicitation for goods or services. Parties responsible for the distribution, promotion or publication of any material containing any solicitation for funds, advertising or solicitation for goods or services agree to pay Fabits One Thousand US Dollars ($1000.00) per account involved with such activities to cover expenses involved with investigation and prosecution of such activities. You will not use the Service to distribute or upload any virus, or malicious software of any type, or do anything else that might cause harm to the Service, the website, Fabits, its systems, or any other members' systems in any way.
                    <br>
                    You will not post or transmit in any manner any contact information including but not limited to email addresses, "instant messenger" nicknames or contact information, telephone numbers, postal addresses, URLs, and full names through publicly posted information on the website and through its Services.
                    <br>
                    You will not cause the Service to be accessed through any automated or robotic means, including but not limited to the rapid access of the site as in a denial-of-service attack. Such restriction shall not websitely to legitimate search engine activity that does not place an unreasonable burden on the Service. You will not use a third-party websitelication such as a mobile smart phone websitelication, social media or other Web page widget, or any other such mobile, social media, Web, or desktop websitelication to access the Service, except where such websitelication is either provided by us or endorsed by us. Such restriction shall not websitely to a browser websitelication which merely displays the pages of the Service in their entirety without modification or reformulation of content.
                    <br>
                    We may use third-party advertising companies, such as Yahoo!, Google, and Microsoft, to serve ads when you visit our website. These companies may use information about your interests in order to provide advertisements about goods and services of interest to you. We reserve the right to monitor all advertisements, public postings and messages to ensure that they conform to content guidelines that are monitored by us and subject to change from time to time.
                    <br>
                    We do not and cannot review all profiles, public postings, messages or other materials posted or sent by users of the Service. We are not responsible for any of the content of these profiles, public postings, messages or other materials. We reserve the right, but are not obligated to, delete, move or edit profiles, public postings, messages and other materials that we, in our sole discretion, deem to be in violation of the Code of Conduct as set out above or any other websitelicable content guidelines or deem to be otherwise unacceptable. You shall remain solely responsible for the content of profiles, public postings, messages and other materials You may upload to the Service.
                    <br>
                    We may, in our sole discretion, terminate or suspend Your access to all or part of the Service at any time, with or without notice, for any reason, including, without limitation, breach of this Agreement. Without limiting the generality of the foregoing, any fraudulent, abusive, or otherwise illegal activity that may otherwise affect the enjoyment of the Service or the Internet by others may be grounds for termination of Your access to all or part of the Service at our sole discretion, and You may be referred to websiteropriate law enforcement agencies.
                    <br>
                    The Service contains information which is proprietary to us, our partners, and our users. We assert full copyright protection in the Service. Information posted by us, our partners or users of the Service may be protected whether or not it is identified as proprietary to us or to them. You agree not to modify, copy or distribute any such information in any manner whatsoever without having first received the express written permission of the owner of such information.
                    <br>
                    You acknowledge that we are not responsible for interruption or suspension of the Service, regardless of the cause of the interruption or suspension.
                    <br>
                    You are responsible for maintaining the confidentiality of Your username and password, and You should not allow anyone to use Your password to access any Services. You are responsible for all usage or activity on the Service by users using Your password, including but not limited to use of Your password by any third party. You agree to immediately notify Fabits of any unauthorized use of Your username or password or any unauthorized access to Your account. For Your own security, it is advisable to log out when You finish each use of the Services, especially if You are using a public computer or share a computer with others. When logging into the Services using a public computer please use caution to prevent other people from learning Your username and password.
                    <br>

                    <br>



                    The Service may contain links to other Internet sites and resources ("External Links"). You acknowledge that we are not responsible for and have no liability as a result of the availability of External Links or their contents. We suggest that You review the terms of use and privacy statements of such External Links prior to using them. You understand that by using any of the External Links, You may encounter content that may be deemed offensive, indecent, or objectionable, which content may or may not be identified as having explicit language, and that the results of any search or entering of a particular URL may automatically and unintentionally generate links or references to objectionable material. Nevertheless, You agree to use the External Links at Your sole risk and that Fabits shall not have any liability to You for content that may be found to be offensive, indecent, or objectionable. It shall be Your sole and exclusive obligation to prevent children and other persons from viewing or accessing any inwebsiteropriate content that may be included in or available through any External Links. By using External Links, You acknowledge and agree that Fabits is not responsible for examining or evaluating the content, accuracy, completeness, timeliness, validity, copyright compliance, legality, decency, quality or any other aspect of such materials at External Links. Fabits does not warrant or endorse and does not assume and will not have any liability or responsibility to You or any other person for any External Links or for any other materials, products, or services of third parties. Fabits shall not be responsible for the contents of, updates to, or privacy practices of third parties operating External Links, which may differ from those of Fabits. The personal data You may choose to give to such third parties are not covered by Fabits's privacy policies. Some third party companies may choose to share their personal data with Fabits, in which case such data sharing shall be governed by that third party's privacy policy. You understand and agree that your use of External Links may result in harmful or unwanted content or malicious software infecting or interacting with your computer or mobile device. You accept all risk in connection with such External Links, and you agree that Fabits shall have no responsibility to you in the event your computer or mobile device is affected in any way by your use of External Links.

                    <br>

                    We respect and uphold individual rights to privacy and the protection of personal information. We know how important it is to protect Your personal information and want to make every customer experience safe and secure. In keeping with that goal, we have developed this Policy to explain our practices for the collection, use, and disclosure of Your personal information. For the purposes of this Policy, “personal information” means information about an identifiable individual, including, for example, an individual’s height, birth date, name, home address, telephone number, social insurance number, sex, income and marital status. We will only collect, use or disclose personal information in accordance with this Policy, or in accordance with laws websitelicable to the collection, use and disclosure of Your personal information by us (“websitelicable Privacy Laws”). We have websiteointed a Privacy Officer who is responsible for our compliance with this Policy. Information on how to contact the Privacy Officer can be found below.

                    <br><br>
                    Fabits.in

                <p>

            </div>

        </div>
        <div class="row ">
            <div class="col-md-12 p-2 pr-3 text-xs-center text-md-right mr-1 " >

                <ul class="nav nav-inline ">
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/terms">Terms & Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/privacy">Privacy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/loginpolicy">Login Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/about">About</a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
@endsection
