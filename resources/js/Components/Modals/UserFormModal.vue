<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import MultiSelect from "primevue/multiselect";

const toast = useToast();

const props = defineProps({
  mode: { type: String, default: "create" },
  userFields: { type: Array, default: () => [] },
  user: { type: Object, default: () => ({}) },
  inputFields: { type: Array, default: () => [] },
  firstDropdown: { type: Array, default: () => [] },
  secondDropdown: { type: Array, default: () => [] },
});

const emit = defineEmits(["submit", "close"]);

const form = useForm({});

// Populate form when editing
watch(
  () => props.user,
  (val) => {
    if (val) {
      Object.assign(form, {
        ...val,
        email: val.user?.email ?? "",
      });
    }
  },
  { immediate: true }
);

// Animation
const isClosing = ref(false);

function closeWithAnimation() {
  isClosing.value = true;
  setTimeout(() => {
    emit("close");
    isClosing.value = false;
  }, 200);
}

function submit() {
  if (props.mode === "edit") {
    form.put(route("user_management.update", form.id), {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Updated",
          detail: "User updated successfully.",
          life: 3000,
        });

        emit("close");
        emit("submit", form);
      },
      onError: (errors) => {
        const firstError = Object.values(errors)[0];

        toast.add({
          severity: "error",
          summary: "Validation Failed",
          detail: firstError,
          life: 4000,
        });
      },
    });
  } else {
    form.post(route("user_management.store"), {
      onSuccess: () => {
        toast.add({
          severity: "success",
          summary: "Created",
          detail: "User added successfully.",
          life: 3000,
        });

        form.reset();
        emit("submit", form);
        emit("close");
      },
      onError: (errors) => {
        const firstError = Object.values(errors)[0];

        toast.add({
          severity: "error",
          summary: "Validation Failed",
          detail: firstError,
          life: 4000,
        });
      },
    });
  }
}
</script>

<template>
  <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
    <div
      :class="[
        'bg-white rounded-lg w-full max-w-lg p-6 overflow-y-auto max-h-[90vh]',
        isClosing ? 'animate-pop-out' : 'animate-pop-in',
      ]"
      @click.stop
    >
      <h3 class="text-2xl font-bold text-[#850038] mb-6">
        {{ mode === "edit" ? "Edit Permissions" : "Add User" }}
      </h3>

      <Toast />

      <!-- FORM -->
      <form @submit.prevent="submit">
        <div class="space-y-4">
          <div class="flex flex-col md:flex-row gap-4">
            <!-- FIRST DROPDOWN -->
            <div v-for="fdp in firstDropdown" :key="fdp.label">
              <label class="block text-[#3B3B3B] text-sm font-bold mb-1">{{
                fdp.label
              }}</label>
              <select
                :class="[
                  'w-full sm:w-[14rem] rounded-md px-3 py-3 bg-[#F8F8F8] text-gray-500 cursor-pointer text-sm focus:ring-1 focus:outline-none',
                ]"
              >
                <option value="">Select</option>
                <option
                  v-for="item in props[fdp.name]"
                  :key="item.id"
                  :value="item[fdp.value]"
                >
                  {{ item[fdp.option] || "N/A" }}
                </option>
              </select>
            </div>

            <!-- SECOND DROPDOWN -->
            <div v-for="sec in secondDropdown" :key="sec.label">
              <label class="block text-[#3B3B3B] text-sm font-bold mb-1">{{
                sec.label
              }}</label>
              <MultiSelect
                v-model="form[sec.label]"
                :options="sec.options"
                optionLabel="label"
                display="chip"
                filter
                placeholder="Select"
                :maxSelectedLabels="3"
                :class="[
                  'w-full sm:w-[14rem] rounded-xl py-1 bg-[#F8F8F8] text-gray-700 text-sm shadow-sm !border-1 !border-black hover:!border-black hover:!shadow-none',
                ]"
              />
            </div>
          </div>

          <!-- INPUT FIELDS -->
          <div v-for="userF in inputFields" :key="userF.model">
            <label class="block text-sm text-[#3B3B3B] font-bold mb-1">
              {{ userF.label }}
            </label>

            <input
              v-model="
                form[userF.model === 'user.email' ? 'email' : userF.model]
              "
              :type="userF.type || 'text'"
              :placeholder="userF.placeholder"
              :class="[
                'w-full rounded-md px-3 py-3 text-[#3B3B3B] bg-[#F8F8F8] text-sm focus:ring-1 focus:outline-none',
              ]"
            />
            <!-- <div v-if="form.errors[userF.model]" class="text-red-500 text-sm">
              {{ form.errors[userF.model] }}
            </div> -->
          </div>
        </div>

        <!-- BUTTONS -->
        <div class="flex justify-end gap-3 mt-6">
          <button
            type="button"
            @click="closeWithAnimation"
            class="border border-gray-400 px-6 py-3 rounded-full text-[#3B3B3B] text-sm font-semibold hover:bg-gray-100"
          >
            Cancel
          </button>

          <button
            type="submit"
            class="bg-[#0E6021] text-white px-8 py-3 rounded-full text-sm font-semibold hover:bg-green-800"
          >
            {{ mode === "edit" ? "Confirm" : "Add" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>