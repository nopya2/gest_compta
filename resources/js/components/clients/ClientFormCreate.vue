<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Formulaire de création</h3>
                    </div>
                    <div class="card-body">
                        <form role="form">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="raison_sociale">Raison sociale</label>
                                        <input type="text" class="form-control" placeholder="Entrez la raion sociale" v-model.trim="$v.client.raison_sociale.$model" name="raison_sociale">
                                        <small class="form-text text-danger" v-if="!$v.client.raison_sociale.required">Champs requis.</small>
                                        <small class="form-text text-danger" v-if="!$v.client.raison_sociale.minLength">{{$v.client.raison_sociale.$params.minLength.min}} caractères minimum.</small>
                                        <small class="form-text text-danger" v-if="!$v.client.raison_sociale.maxLength">{{$v.client.raison_sociale.$params.maxLength.max}} caractères maximum.</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nif">NIF</label>
                                        <input type="text" class="form-control" placeholder="Entrez le NIF" v-model.trim="$v.client.nif.$model" name="nif">
                                        <small class="form-text text-danger" v-if="!$v.client.nif.required">Champs requis.</small>
                                        <small class="form-text text-danger" v-if="!$v.client.nif.minLength">{{$v.client.nif.$params.minLength.min}} caractères minimum.</small>
                                        <small class="form-text text-danger" v-if="!$v.client.nif.maxLength">{{$v.client.nif.$params.maxLength.max}} caractères maximum.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="bp">Boite postale</label>
                                        <input type="text" class="form-control" placeholder="Entrez la boite postale" v-model.trim="$v.client.bp.$model" name="bp">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="adresses">Adresse</label>
                                        <textarea class="form-control" placeholder="Entrez l'adresse" v-model="$v.client.adresse.$model" name="adresse"></textarea>
                                        <small class="form-text text-danger" v-if="!$v.client.adresse.required">Champs requis.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="ville">Ville</label>
                                        <select class="form-control" v-model="$v.client.ville.$model" name="ville">
                                            <option v-for="item in villes" :key="item" :value="item">{{ item }}</option>
                                        </select>
                                        <small class="form-text text-danger" v-if="!$v.client.ville.required">Champs requis.</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="pays">Pays</label>
                                        <select class="form-control" v-model="$v.client.pays.$model" name="pays">
                                            <option v-for="item in pays" :key="item">{{ item }}</option>
                                        </select>
                                        <small class="form-text text-danger" v-if="!$v.client.pays.required">Champs requis.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="tel">Téléphone de l'entreprise</label>
                                        <input type="text" class="form-control" placeholder="Entrez le téléphone de l'entreprise" v-model="$v.client.tel.$model" name="tel">
                                        <small class="form-text text-danger" v-if="!$v.client.tel.required">Champs requis.</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="responsable">Responsable/Référend</label>
                                        <input type="text" class="form-control" placeholder="Identité de la ressource" v-model="$v.client.responsable.$model" name="responsable">
                                        <small class="form-text text-danger" v-if="!$v.client.responsable.required">Champs requis.</small>
                                        <small class="form-text text-danger" v-if="!$v.client.responsable.minLength">{{$v.client.responsable.$params.minLength.min}} caractères minimum.</small>
                                        <small class="form-text text-danger" v-if="!$v.client.responsable.maxLength">{{$v.client.responsable.$params.maxLength.max}} caractères maximum.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label form="tel_responsable">Téléphone responsable</label>
                                        <input type="text" class="form-control" placeholder="Tapez le numéro de téléphone" v-model="$v.client.tel_responsable.$model" name="tel_responsable">
                                        <small class="form-text text-danger" v-if="!$v.client.tel_responsable.required">Champs requis.</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input type="email" class="form-control" placeholder="Tapez l'e-mail" v-model="$v.client.email.$model" name="email">
                                        <small class="form-text text-danger" v-if="!$v.client.email.required">Champs requis.</small>
                                        <small class="form-text text-danger" v-if="!$v.client.email.email">E-mail invalide.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-md-center">
                                <div class="col">
                                    <button class="btn btn-success" :disabled="$v.client.$invalid" type="button" @click="create()" v-if="!btnLoading">
                                        <i class="fas fa-save mr-1"></i>Créer
                                    </button>
                                    <a :href="'/clients'" v-if="!btnLoading">
                                        <button class="btn btn-danger" type="button">
                                            <i class="fas fa-times mr-1"></i>Annuler
                                        </button>
                                    </a>
                                    <button class="btn btn-primary shadow-2" type="button" disabled="" v-if="btnLoading">
                                        <span class="spinner-grow spinner-grow-sm" role="status"></span>
                                        Création en cours...
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Client from './../../models/client'
    import { required, minLength, maxLength, between, email, sameAs } from 'vuelidate/lib/validators';

    export default {

        props : [],

        data(){
            return{
                client: new Client(),
                villes: [],
                pays: [],
                filter: {
                    keyword: '',
                },
                btnLoading: false,
                api_token: ''
            }
        },
        validations: {
            client: {
                raison_sociale: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(100),
                },
                nif: {
                    required,
                    minLength: minLength(1),
                    maxLength: maxLength(50),
                },
                bp: {
                },
                adresse: {
                    required
                },
                ville: {
                    required
                },
                pays: {
                    required
                },
                tel: {
                    required
                },
                responsable: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(100),
                },
                tel_responsable: {
                    required
                },
                email: {
                    email,
                    required
                }
            }
        },
        created(){

            if (window.localStorage.getItem('authUser')) {
                const authUser = JSON.parse(window.localStorage.getItem('authUser'))
                this.api_token = authUser.api_token
            }

            this.fetchVilles()
            this.fetchPays()

        },

        methods: {
            fetchVilles(){
                fetch('/api/resources/villes')
                    .then(res => res.json())
                    .then(res => {
                        this.villes = res.villes
                    })
                    .catch(error => {
                        toastr.error('Erreur chargement des villes!.')
                    });
            },
            fetchPays(){
                fetch('/api/resources/pays')
                    .then(res => res.json())
                    .then(res => {
                        this.pays = res.pays
                    })
                    .catch(error => {
                        toastr.error('Erreur chargement des pays!.')
                    });
            },
            create(){
                let vm = this;
                vm.btnLoading = true

                fetch(`/api/clients?api_token=${this.api_token}`, {
                    method: 'post',
                    body: JSON.stringify(this.client),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        vm.btnLoading = false
                        vm.client = new Client()
                        toastr.success('Client créé!')
                    })
                    .catch(error => {
                        vm.btnLoading = false
                        toastr.error('Erreur création du client!.')
                    });

            },
        }

    }
</script>
