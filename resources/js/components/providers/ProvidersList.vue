<template>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des fournisseurs</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" @click="exportProviders()">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" @click="printProviders()">
                            <i class="fas fa-print mr-1"></i> Imprimer PDF
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-3" v-if="spinnerProviders">
                        <div class="spinner-grow text-warning" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="!spinnerProviders">
                        <table class="table m-0" id="table-providers">
                            <thead>
                            <tr>
                                <th width="100px">Nom</th>
                                <th>NIF</th>
                                <th>Montant Engagé</th>
                                <th>Montant payé</th>
                                <th>Solde</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="provider in providers" @click="loadProviderData(provider.name)" v-bind:class="{ 'bg-info':  provider.name == selectedProvider}">
                                <td>{{ provider.name }}</td>
                                <td>{{ provider.nif }}{{ provider.code_nif }}</td>
                                <td class="text-center"><small>{{ provider.mt_engage|numFormat }}</small></td>
                                <td class="text-center"><small>{{ provider.mt_paye|numFormat }}</small></td>
                                <td class="text-center"><small>{{ provider.solde|numFormat }}</small></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-6 col-sm-9 col-xs-9 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Détails du fournisseur
                        <div class="spinner-border spinner-border-sm text-warning align-right" role="status" v-if="spinnerDetails">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h3>
                    <div class="card-tools">
                        <button v-if="selectedProvider != ''" type="button" class="btn btn-secondary btn-sm" @click="printProvider(selectedProvider)">
                            <i class="fas fa-print mr-1"></i> Imprimer
                        </button>
                        <!-- <button v-if="selectedProvider != ''" type="button" class="btn btn-info btn-sm" @click="printHistory(selectedProvider)">
                            <i class="fas fa-history mr-1"></i> Situation
                        </button> -->
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <h4 v-if="selectedProvider == ''" class="text-muted text-center">Veuillez sélectionner un fournisseur pour afficher les détails</h4>
                    <div class="row" v-if="selectedProvider != ''">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill"
                                               href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home"
                                               aria-selected="true">Engagements ({{ paginationEngagement.total }})</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill"
                                               href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile"
                                               aria-selected="false">Paiements ({{ paginationEchelon.total }})</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="custom-content-below-tabContent">
                                        <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                            <div class="row mt-3 mb-3">
                                                <div class="col-md-9">
                                                    <button class="btn btn-info btn-sm">
                                                        Total Engagés<br>
                                                        {{ amounts.engage|numFormat }}
                                                    </button>
                                                    <button class="btn btn-success btn-sm">
                                                        Total Payés<br>
                                                        {{ amounts.paye|numFormat }}
                                                    </button>
                                                    <button class="btn btn-danger btn-sm">
                                                        Total Non Payés<br>
                                                        {{ amounts.non_paye|numFormat }}
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="far fa-calendar-alt"></i>
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm float-right calendrier"
                                                                   @change="searchEngagement">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control form-control-sm" placeholder="Votre recherche" v-model="filter.engagement" v-on:input="searchEngagement"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr style="text-align: center;">
                                                        <th>N&deg; engagement</th>
                                                        <th>N&deg; devis</th>
                                                        <th>Intitulé de la dépense</th>
                                                        <th>Montant engagé</th>
                                                        <th>Montant payé</th>
                                                        <th>Date</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 14px">
                                                    <tr>
                                                        <td colspan="6" v-if="engagements.length <= 0">
                                                            Aucun engagement
                                                        </td>
                                                    </tr>
                                                    <tr v-for="(engagement, index) in engagements" v-bind:class="{ 'text-success':  engagement.is_paid}">
                                                        <td>{{ engagement.n_engage }}</td>
                                                        <td>{{ engagement.n_devis }}</td>
                                                        <td>{{ engagement.l_depeng }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-warning">{{ engagement.m_engage|numFormat }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-info">{{ engagement.m_paye|numFormat }}</span>
                                                        </td>
                                                        <td>
                                                            <span v-if="engagement.date_paiement">{{ engagement.date_paiement|moment('DD/MM/Y') }}</span>
                                                            <span v-if="!engagement.date_paiement">N/A</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination justify-content-end">
                                                    <li class="page-item" v-bind:class="[{disabled: !paginationEngagement.prev_page_url}]">
                                                        <a class="page-link" href="javascript:" tabindex="-1" @click="fetchEngagements(paginationEngagement.prev_page_url)">Précédent</a>
                                                    </li>
                                                    <li class="page-item disabled"><a class="page-link" href="javascript:">{{ paginationEngagement.current_page }}</a></li>
                                                    <!--<li class="page-item"><a class="page-link" href="#!">2</a></li>-->
                                                    <!--<li class="page-item"><a class="page-link" href="#!">3</a></li>-->
                                                    <li class="page-item" v-bind:class="[{disabled: !paginationEngagement.next_page_url}]">
                                                        <a class="page-link" href="javascript:" @click="fetchEngagements(paginationEngagement.next_page_url)">Suivant</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                        <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                            <div class="row mt-3">
                                                <div class="col-md-3 offset-md-9">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Votre recherche" v-model="filter.echelon" @change="searchEchelon"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr style="text-align: center;">
                                                        <th>N&deg; engagement</th>
                                                        <th>N&deg; devis</th>
                                                        <th>Montant payé</th>
                                                        <th>Date</th>
                                                        <th>Pièces jointes</th>
                                                        <th>Utilisateur</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 14px">
                                                    <tr>
                                                        <td colspan="6" v-if="echelons.length <= 0">
                                                            Aucun paiement
                                                        </td>
                                                    </tr>
                                                    <tr v-for="(echelon, index) in echelons">
                                                        <td>{{ echelon.engagement.n_engage }}</td>
                                                        <td>{{ echelon.engagement.n_devis }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-info">{{ echelon.m_paye|numFormat }}</span>
                                                        </td>
                                                        <td>
                                                            <span v-if="echelon.date_paiement">{{ echelon.date_paiement|moment('DD/MM/Y') }}</span>
                                                            <span v-if="!echelon.date_paiement">N/A</span>
                                                        </td>
                                                        <td>
                                                            <ul>
                                                                <li v-for="document in echelon.documents">
                                                                    {{ document.filename }}
                                                                    <a :href="`/uploads/documents/${document.filename}`" target="_blank">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                        <td>{{ echelon.user.name }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination justify-content-end">
                                                    <li class="page-item" v-bind:class="[{disabled: !paginationEchelon.prev_page_url}]">
                                                        <a class="page-link" href="javascript:" tabindex="-1" @click="fetchEchelons(paginationEchelon.prev_page_url)">Précédent</a>
                                                    </li>
                                                    <li class="page-item disabled"><a class="page-link" href="javascript:">{{ paginationEchelon.current_page }}</a></li>
                                                    <!--<li class="page-item"><a class="page-link" href="#!">2</a></li>-->
                                                    <!--<li class="page-item"><a class="page-link" href="#!">3</a></li>-->
                                                    <li class="page-item" v-bind:class="[{disabled: !paginationEchelon.next_page_url}]">
                                                        <a class="page-link" href="javascript:" @click="fetchEchelons(paginationEchelon.next_page_url)">Suivant</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<table class="table table-bordered" id="table-engagements">-->
                                <!--<thead>-->
                                <!--<tr>-->
                                    <!--<th>N° engagement</th>-->
                                    <!--<th>N° devis</th>-->
                                    <!--<th>Intitulé dépense</th>-->
                                    <!--<th>Montant</th>-->
                                    <!--<th>Date</th>-->
                                <!--</tr>-->
                                <!--</thead>-->
                                <!--<tbody>-->
                                <!--&lt;!&ndash;<tr v-for="engagement in engagements">&ndash;&gt;-->
                                    <!--&lt;!&ndash;<td>{{ engagement.n_engage }}</td>&ndash;&gt;-->
                                    <!--&lt;!&ndash;<td>{{ engagement.n_devis }}</td>&ndash;&gt;-->
                                    <!--&lt;!&ndash;<td>{{ engagement.l_depeng }}</td>&ndash;&gt;-->
                                    <!--&lt;!&ndash;<td class="text-center"><span class="badge badge-info">{{ engagement.m_paye|numFormat }}</span></td>&ndash;&gt;-->
                                    <!--&lt;!&ndash;<td>{{ engagement.date_paiement }}</td>&ndash;&gt;-->
                                <!--&lt;!&ndash;</tr>&ndash;&gt;-->
                                <!--</tbody>-->
                            <!--</table>-->
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

            </div>
        </div>

        <!-- Modal sélection de la période pour l'impression de la situation des fournisseurs-->
        <div class="modal fade" id="modal-periode">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Séléction de la période</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right periode">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer float-right">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="confirmPrintProviders()">
                            <i class="mr-1 fas fa-print"></i>Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div> <!-- /.modal-edit -->
    </div>
</template>

<script>
    export default {
        props : [],
        mounted() {
        },
        data(){
            return{
                providers: [],
                engagements: [],
                amounts: {
                    engage: 0,
                    paye: 0,
                    non_paye: 0
                },
                echelons: [],
                filter: {
                    engagement: '',
                    echelon: '',
                    start: '',
                    end: ''
                },
                spinner: false,
                api_token: '',
                paginationEngagement: {
                    current_page: 1,
                    total: 0
                },
                paginationEchelon: {
                    current_page: 1,
                    total: 0
                },
                selectedProvider : '',
                spinnerProviders: true,
                spinnerDetails: false,
                calendrier: null,
                periode: {
                    start: '',
                    end: ''
                }


            }
        },

        created(){

            if (window.localStorage.getItem('authUser')) {
                const authUser = JSON.parse(window.localStorage.getItem('authUser'))
                this.api_token = authUser.api_token

                this.fetchProviders()
            }

            this.filter.start = moment().startOf('year').format('YYYY-MM-DD')
            this.filter.end = moment().format('YYYY-MM-DD')

        },

        methods: {
            fetchProviders(page_url) {
                let vm = this;
                this.spinner = true;

                page_url = page_url || `/api/providers?api_token=${this.api_token}`
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.spinnerProviders = false
                        this.providers = res.data

                        $(function () {
                            $("#table-providers").DataTable({
                                "lengthChange": false,
                            });
                        });
                    })
                    .catch(error => {
                        toastr.error('Erreur chargement des données!.')
                        this.spinnerProviders = false
                    });
            },
            searchEngagement(){
                this.fetchEngagements();
            },
            searchEchelon(){
                this.fetchEchelons()
            },
            loadProviderData(provider){
                let vm = this
                this.spinnerDetails = true
                this.selectedProvider = provider
                setTimeout(function () {
                    $('.calendrier').daterangepicker({
                        startDate:moment().startOf('year'),
                        endDate  : moment(),
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    })

                    $('.calendrier').on('apply.daterangepicker', function(ev, picker) {
                        vm.filter.start = picker.startDate.format('YYYY-MM-DD');
                        vm.filter.end = picker.endDate.format('YYYY-MM-DD');

                        vm.fetchEngagements()
                    });
                }, 100)
                vm.fetchEngagements()
                // vm.fetchEchelons()

            },
            fetchEngagements(page){
                this.spinnerDetails = true
                let vm = this;

                let url_parameters = `api_token=${this.api_token}&provider=${this.selectedProvider}&keyword=${this.filter.engagement}`
                    +`&start=${this.filter.start}&end=${this.filter.end}`
                let page_url = `/api/provider/engagements?${url_parameters}`
                if(page) page_url = `${page}&${url_parameters}`

                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.engagements = res.resources.data
                        this.amounts = {
                            engage: res.m_engage,
                            paye: res.m_paye,
                            non_paye: res.m_non_paye
                        }
                        let meta = {
                            current_page: res.resources.current_page,
                            total: res.resources.total,
                            last_page: res.last_page,
                        }

                        let links = {
                            next: res.resources.next_page_url,
                            prev: res.resources.prev_page_url,
                        }
                        vm.makePaginationEngagement(meta, links)

                        vm.fetchEchelons()
                    })
                    .catch(error => {
                        toastr.error('Erreur chargement des données!.')
                        this.spinnerProviders = false
                    });
            },
            fetchEchelons(page){
                let vm = this

                let url_paramters = `api_token=${this.api_token}&provider=${this.selectedProvider}&keyword=${this.filter.echelon}`
                let page_url = `/api/provider/echelons?${url_paramters}`
                if(page) page_url = `${page}&${url_paramters}`

                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.echelons = res.data
                        vm.makePaginationEchelon(res.meta, res.links)
                        this.spinnerDetails = false
                    })
                    .catch(error => {
                        toastr.error('Erreur chargement des données!.')
                        this.spinnerProviders = false
                    });
            },
            makePaginationEngagement(meta, links){
                let pagination = {
                    current_page: meta.current_page,
                    total: meta.total,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev,
                }

                this.paginationEngagement = pagination;
            },
            makePaginationEchelon(meta, links){
                let pagination = {
                    current_page: meta.current_page,
                    total: meta.total,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev,
                }

                this.paginationEchelon = pagination;
            },
            printProvider(provider){
                window.open(`/providers/provider-pdf?provider=${provider}&start=${this.filter.start}&end=${this.filter.end}`, '_blank')
            },
            printHistory(provider){
                window.open(`/providers/history?provider=${provider}`, '_blank')
            },
            printProviders(){
                let vm = this
                vm.periode.start = moment().startOf('year').format('YYYY-MM-DD');
                vm.periode.end = moment().format('YYYY-MM-DD');

                $('#modal-periode').modal('show');
                setTimeout(function () {
                    $('.periode').daterangepicker({
                        // multipleDatePicker: true,
                        // showDropdowns: true,
                        // minYear: moment().startOf('year'),
                        // maxYear: moment(),
                        startDate:moment().startOf('year'),
                        endDate  : moment(),
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    })
                    $('.periode').on('apply.daterangepicker', function(ev, picker) {
                        vm.periode.start = picker.startDate.format('YYYY-MM-DD');
                        vm.periode.end = picker.endDate.format('YYYY-MM-DD');
                    });
                }, 100)
                // window.open('/providers/pdf/situation-fournisseurs', '_blank')
            },
            confirmPrintProviders(){
                window.open(`/providers/pdf/situation-fournisseurs?start=${this.periode.start}&end=${this.periode.end}`, '_blank')
            },
            exportProviders(){
                window.open(`/providers/export`, '_blank')
            }

        }

    }
</script>
