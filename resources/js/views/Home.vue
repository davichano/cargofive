<template>
    <section>
        <div class="row justify-content-center mb-4">
            <div class="col-md-4 text-center">
                <h1>Upload Excel File</h1>
                <hr>
                <input type="file" name="excel" id="excel">
                <button class="btn btn-primary m-2" v-on:click="upload">Upload</button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card bg-white">
                    <div class="card-header">
                        Surcharges
                        <div class="btn btn-primary btn-sm float-end" v-on:click="joinGroups">
                            Join Groups
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="accordion">
                            <div class="accordion-item" v-for="(surcharge, index) in surcharges">
                                <h2 class="accordion-header">
                                    <input type="checkbox" v-model="selected" :value="surcharge.id">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            :data-bs-target="'#collapse'+surcharge.id" aria-expanded="false"
                                            aria-controls="collapseOne">
                                        {{ index + 1 }} - {{ surcharge.name }}
                                    </button>
                                </h2>
                                <div :id="'collapse'+surcharge.id" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                <ul class="list-group">
                                                    <li class="list-group-item active" aria-current="true">
                                                        Grouped Surcharges
                                                    </li>
                                                    <li v-for="son in surcharge.sons" class="list-group-item">
                                                        {{ son.name }}
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-3">
                                                <ul class="list-group">
                                                    <li class="list-group-item active" aria-current="true">
                                                        Freight Rates
                                                    </li>
                                                    <li v-for="rate in surcharge.rates" class="list-group-item">
                                                        {{ rate.amount }} {{ rate.currency }}
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
            surcharges: [],
            selected: []
        }
    },
    watch: {
        selected: {
            handler(val) {
                if (val.length > 2) {
                    val.shift();
                }
            },
            deep: true
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
        upload: async function () {
            let url = urlHome + "/api/surcharges/updateExcel"
            let excel = document.getElementById('excel').files[0];
            console.log(excel);
            if (!excel.type.match(/^application\/vnd\.ms-excel|application\/vnd\.openxmlformats-officedocument\.spreadsheetml\.sheet$/)) {
                alert('Please select an Excel file.');
                return;
            }
            let formData = new FormData();
            formData.append('excel', excel);
            let response = await axios.post(url, formData)
            if (response.data) {
                await this.initList();
                alert("Loading complete!");
            } else alert("Error loading the data");
        },
        joinGroups: async function () {
            if (this.selected.length !== 2) {
                alert("Select 2 groups");
                return;
            }
            let url = urlHome + "/api/surcharges/joinGroups"
            let response = await axios.post(url, {idGroupA: this.selected[0], idGroupB: this.selected[1]});
            if (response.data) {
                this.selected = [];
                await this.initList();
                alert("Joined!");
            } else alert("Error in the groups' joining");
        }
    },
    mounted() {
        this.initList();
    }
}
</script>

<style scoped>
.accordion-header {
    position: relative;
}

.accordion-header input {
    position: absolute;
    z-index: 10;
    top: 40%;
    left: 10px;
}

.accordion-header .accordion-button {
    padding-left: 30px;
}
</style>
