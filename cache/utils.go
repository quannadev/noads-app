package cache

import (
	"crypto/sha256"
	"encoding/json"
)

func HashKey(link string) string {
	//hash link to hex string
	bytes := []byte(link)
	hash := sha256.Sum256(bytes)
	return string(hash[:])
}

func EncodeToByte(data interface{}) ([]byte, error) {
	bytes, err := json.Marshal(data)
	if err != nil {
		return nil, err
	}
	return bytes, nil
}

func DecodeToByte(data []byte, v interface{}) error {
	return json.Unmarshal(data, v)
}
