import sys
import json

def get_piped_data():
    try:
        decoded = json.loads(input().strip())
        return decoded
    except ValueError:
        raise ValueError('Error: invalid input.')



def output(data):
    print(json.dumps(data))

"""
Main program
"""
if __name__ == '__main__':
    raise Exception('Something went wrong.')

