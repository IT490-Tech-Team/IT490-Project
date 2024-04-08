import difflib
import os
import shutil
import subprocess
import sys
import tempfile
import zipfile

def copy_remote_folder(remote_host, remote_path, local_path):
    # Create a temporary directory to store the copied folder
    os.makedirs(local_path, exist_ok=True)

    # Execute SCP command to copy the remote folder to the local machine
    scp_command = f"scp -r {remote_host}:{remote_path}/* {local_path}"
    try:
        subprocess.run(scp_command, shell=True, check=True)
        print(f"Copied folder from {remote_host}:{remote_path} to {local_path}")
    except subprocess.CalledProcessError as e:
        print(f"Error copying folder from remote server: {e}")
        
environments = [
	{
		"name": "dev"
	},
	{
		"name": "dev"
	},
	{
		"name": "dev"
	},
]