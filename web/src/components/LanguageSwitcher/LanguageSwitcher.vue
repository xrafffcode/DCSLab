<script setup lang="ts">
import { Menu } from "@/components/Base/Headless";
import { switchLang } from "@/lang";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const switchLanguage = (lang: "en" | "id"): void => {
  switchLang(lang);
}
</script>

<tempate>
    <Menu class="mr-4 intro-x sm:mr-6">
        <Menu.Button :as="Button" variant="primary">
            <Lucide icon="Globe" />
        </Menu.Button>
        <Menu.Items class="w-48 h-24 overflow-y-auto" placement="bottom-end">
            <Menu.Item @click="switchLanguage('en')">
                <span class="text-primary">{{ t('components.top-bar.language.english') }}</span>
            </Menu.Item>
            <Menu.Item @click="switchLanguage('id')">
                <span class="text-primary">{{ t('components.top-bar.language.indonesia') }}</span>
            </Menu.Item>
        </Menu.Items>
    </Menu>
</tempate>