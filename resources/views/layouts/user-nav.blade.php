<!-- sidebar -->
<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <router-link tag="li" :to="{ name: 'dashboard' }" class="">
                <a><i class="icon-speedometer"></i> <span class="nav-text">Dashboard</span></a>
            </router-link>
            <li><a class="has-arrow" href="#" aria-expanded="false"><i class="icon-people"></i> <span class="nav-text">Profile</span> </a>
                <ul aria-expanded="false">
                    <li>
                        <router-link :to="{ name: 'profile' }" >User Profile</router-link>
                    </li>
                </ul>
            </li>

            <li><a class="has-arrow" href="#" aria-expanded="false"><i class="icon-layers"></i> <span class="nav-text">Help</span></a>
                <ul aria-expanded="false">
                    <li>
                        <router-link :to="{ name: 'provide-help' }" >Provide Help</router-link>
                    </li>
                    <li>
                        <router-link :to="{ name: 'provide-help-report' }" >Provide Help Report</router-link>
                    </li>
                    <li>
                        <router-link :to="{ name: 'get-help-report' }" >Get Help Report</router-link>
                    </li>
                </ul>
            </li>

            <li><a class="has-arrow" href="#" aria-expanded="false"><i class="icon-chart"></i> <span class="nav-text">Genealogy</span></a>
                <ul aria-expanded="false">
                    <li>
                        <router-link :to="{ name: 'level-view' }" >Level View</router-link>
                    </li>
                </ul>
            </li>
            <li><a class="has-arrow" href="#" aria-expanded="false"><i class="icon-social-dropbox"></i> <span class="nav-text">Income Report</span></a>
                <ul aria-expanded="false">
                    <li>
                        <router-link :to="{ name: 'level-income-report' }" >Level Income Report</router-link>
                    </li>
                </ul>
            </li>
            <li><a class="has-arrow" href="#" aria-expanded="false"><i class="icon-briefcase"></i> <span class="nav-text">Withdrawal</span></a>
                <ul aria-expanded="false">
                    <li>
                        <router-link :to="{ name: 'make-withdrawal' }" >Make Withdrawal</router-link>
                    </li>
                    <li>
                        <router-link :to="{ name: 'withdrawal-history' }" >Withdrawal History</router-link>
                    </li>
                </ul>
            </li>
            <li><a class="has-arrow" href="#" aria-expanded="false"><i class="icon-lock"></i> <span class="nav-text"> Security </span></a>
                <ul aria-expanded="false">
                    <li>
                        <router-link :to="{ name: 'two-factor-authentication' }" >Two Factor Authenticator</router-link>
                    </li>
                </ul>
            </li>
            <router-link tag="li" :to="{ name: 'support-center' }" class="">
                <a><i class="icon-note"></i> <span class="nav-text">Support Center</span></a>
            </router-link>
             <router-link tag="li" :to="{ name: 'transaction' }" class="">
                <a><i class="icon-list"></i> <span class="nav-text">Transaction</span></a>
            </router-link>
        </ul>
    </div>
    <!-- #/ nk nav scroll -->
</div>
<!-- #/ sidebar -->