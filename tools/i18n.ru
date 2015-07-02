require 'yaml'
class Hash
   def deep_merge(hash)
      target = dup

      hash.keys.each do |key|
         if hash[key].is_a? Hash and self[key].is_a? Hash
            target[key] = target[key].deep_merge(hash[key])
            next
         end
         target[key] = hash[key]
      end
      target
   end

   def fill_all_values value
      each_key do |key|
         if self[key].is_a?(String)
            store(key,value)
         else
            self[key].fill_all_values value if self[key].is_a?(Hash)
         end
      end
   end
end 
def merge_yaml_i18n_files(locale_code_A,locale_code_B,untranslated_message)
   hash_A = YAML.load_file("../app/Resources/translations/messages.#{locale_code_A}.yml")
   hash_B = YAML.load_file("../app/Resources/translations/messages.#{locale_code_B}.yml")
 
 
   hash_A_ut = Marshal.load(Marshal.dump(hash_A))
   hash_A_ut.fill_all_values(untranslated_message)
 
   hash_B_ut = Marshal.load(Marshal.dump(hash_B))
   hash_B_ut.fill_all_values(untranslated_message)
 
 
   hash_A = hash_B_ut.deep_merge(hash_A)
   hash_B = hash_A_ut.deep_merge(hash_B)
 
   puts hash_A.to_yaml
   puts hash_B.to_yaml
end
 
merge_yaml_i18n_files('tr','en','untranslated')
