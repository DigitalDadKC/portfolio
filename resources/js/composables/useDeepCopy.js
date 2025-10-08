export function useDeepCopy() {
    const deepCopy = (input) => {
        if (Array.isArray(input)) {
          return input.map((element) => deepCopy(element));
        } else if (typeof input === "object" && input !== null) {
          let copy = {};
          for (let key in input) {
            copy[key] = deepCopy(input[key]);
          }
          return copy;
        } else {
          return input;
        }
    }

    return {deepCopy}
}
