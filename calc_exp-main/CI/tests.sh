#!/bin/bash

FAIL=0

if [ $FAIL -ne 0 ]; then
  echo "🚨 Alguns testes falharam!"
  exit 1
else
  echo "🎉 Todos os testes passaram!"
  exit 0
fi
