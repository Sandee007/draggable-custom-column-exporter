<template>
    <Head title="Export Format" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                {{ IS_EDIT_MODE ? 'Edit' : 'Create' }} Format
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="grid grid-cols-12">
                        <p
                            class="col-start-2 col-span-4 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                        >
                            Available Columns <br>
                            Click to Select/Remove <hr>
                        </p>
                        <p
                            class="col-start-8 col-span-4 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                        >
                            Selected Columns <br />
                            Drag to Change Order <hr>
                        </p>
                    </div>
                    <div class="grid grid-cols-12">
                        <div class="col-start-2 col-span-4">
                            <div
                                v-for="(column, index_column) in propColumns"
                                :key="index_column"
                                :class="` items-center bg-gray-800 dark:bg-gray-200 px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 my-1 ${getBgColor(
                                    column
                                )}`"
                                @click="columnSelectHandler(column)"
                            >
                                {{ column }}
                            </div>
                        </div>
                        <div class="col-start-8 col-span-4">
                            <draggable
                                v-model="selectedColumns"
                                group="selectedColumns"
                                @start="drag = true"
                                @end="drag = false"
                                item-key="id"
                            >
                                <template #item="{ element }">
                                    <div
                                        class="items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 my-1"
                                    >
                                        {{ element }}
                                    </div>
                                </template>
                            </draggable>
                        </div>
                    </div>

                    <div class="my-3 grid grid-cols-12">
                        <div
                            class="flex justify-between col-start-2 col-span-10"
                        >
                            <div>
                                <InputLabel for="name" value="Format Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>
                            <PrimaryButton
                                :disabled="form.processing"
                                @click="submit"
                                class="dark:bg-green-200 h-10"
                                >   {{ IS_EDIT_MODE ? 'Update' : 'Create' }}</PrimaryButton
                            >
                        </div>
                    </div>

                    <div class="my-3 grid grid-cols-12">
                        <div class="col-start-6 col-span-6">
                            <InputError
                                class="mt-2"
                                :message="form.errors.custom_exception"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import draggable from "vuedraggable";

export default {
    props: ["propColumns", 'editExportFormat'],

    components: {
        PrimaryButton,
        AuthenticatedLayout,
        Head,
        Link,
        draggable,
        InputError,
        InputLabel,
        TextInput,
    },

    mounted(){
        this.setSelectedColumns()
    },

    data() {
        return {
            IS_EDIT_MODE: !!this.$props?.editExportFormat,
            drag: false,
            selectedColumns: [],
            form: useForm({
                id: this?.editExportFormat?.id ?? null,
                name: this?.editExportFormat?.name ?? '',
            }),
        };
    },

    methods: {
        setSelectedColumns(){
            if(this.IS_EDIT_MODE) {
                this.selectedColumns =   this.$props?.editExportFormat?.selected_columns
            } else {
                this.selectedColumns =  this.$props?.propColumns?.filter((i) => true)
            }
        },

        exportFile() {
            window.location = route("export");
        },

        isColumnSelected(column) {
            return this?.selectedColumns?.includes(column) ?? false;
        },

        getBgColor(column) {
            return this.isColumnSelected(column)
                ? " bg-green-800 dark:bg-green-200 "
                : " bg-red-800 dark:bg-red-200 ";
        },

        columnSelectHandler(column) {
            if (this.isColumnSelected(column)) {
                const index = this?.selectedColumns?.indexOf(column);
                if (index > -1) this?.selectedColumns?.splice(index, 1);
                return true;
            }

            this?.selectedColumns?.push(column);
        },

        submit() {
            const routeUrl = this.IS_EDIT_MODE ? "file.exporter.update" : "file.exporter.store"

            this.form
                .clearErrors()
                .transform((data) => ({
                    ...data,
                    selectedColumns: this?.selectedColumns,
                }))
                .post(
                    route(routeUrl), {
                    preserveScroll: true,
                });
        },
    },
};
</script>
<style lang=""></style>
