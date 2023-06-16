<template>
    <section>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card bg-white">
                    <div class="card-header">
                        Surcharges
                        <div class="btn btn-primary btn-sm float-end" v-on:click="group">
                            Group
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="accordion">
                            <div class="accordion-item" v-for="surcharge in surcharges">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            :data-bs-target="'#collapse'+surcharge.id" aria-expanded="false"
                                            aria-controls="collapseOne">
                                        {{ surcharge.name }}
                                    </button>
                                </h2>
                                <div :id="'collapse'+surcharge.id" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <li v-for="son in surcharge.sons" class="list-group-item">{{
                                                    son.name
                                                }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "Home",
    data() {
        return {
            surcharges: []
        }
    },
    methods: {
        initList: async function () {
            try {
                let url = urlHome + "/api/surcharges/getAllFathers"
                let response = await axios.get(url);
                this.surcharges = response.data;
            } catch (e) {
                console.log(e);
                alert("Error loading the data");
            }
        },
        group: async function () {
            let url = urlHome + "/api/surcharges/group"
            let response = await axios.get(url);
            this.surcharges = response.data;
        }
    },
    mounted() {
        this.initList();
    }
}
</script>

<style scoped>

</style>
