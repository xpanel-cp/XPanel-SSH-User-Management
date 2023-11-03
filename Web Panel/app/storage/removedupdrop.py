import json

file_path = "/var/www/html/app/storage/dropbear.json"
with open(file_path, 'r') as file:
    data = json.load(file)
unique_entries = []
entry_seen = {}
for entry in data:
    user = entry['user']
    pid = entry['PID']

    user = str(user)
    pid = str(pid)

    entry_key = f"{user}_{pid}"
    if entry_key not in entry_seen:
        unique_entries.append(entry)
        entry_seen[entry_key] = True

with open(file_path, 'w') as file:
    json.dump(unique_entries, file, indent=2)

print("موارد تکراری حذف شدند.")
